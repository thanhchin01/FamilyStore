 <?php

    namespace App\Http\Controllers\Api\Client;

    use App\Http\Controllers\Controller;
    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Validation\ValidationException;

    class AuthController extends Controller
    {
        protected $otpService;

        public function __construct(\App\Services\Common\OtpService $otpService)
        {
            $this->otpService = $otpService;
        }

        /**
         * Đăng nhập và lấy token (Hỗ trợ Email hoặc SĐT)
         */
        public function login(Request $request)
        {
            $request->validate([
                'identifier'  => 'required', // Email hoặc SĐT
                'password'    => 'required',
                'device_name' => 'required',
            ]);

            // Tìm user theo email HOẶC số điện thoại
            $user = User::where('email', $request->identifier)
                ->orWhere('phone', $request->identifier)
                ->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Thông tin đăng nhập không chính xác.'
                ], 401);
            }

            // Tạo token mới
            $token = $user->createToken($request->device_name)->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công',
                'token'   => $token,
                'user'    => $user
            ]);
        }

        /**
         * Yêu cầu mã OTP để đăng ký
         */
        public function requestRegisterOtp(Request $request)
        {
            $request->validate([
                'identifier' => 'required', // Có thể là email hoặc sđt
                'type'       => 'required|in:email,phone'
            ]);

            $identifier = $request->identifier;

            // Kiểm tra xem đã có tài khoản chưa
            $field = $request->type === 'email' ? 'email' : 'phone';
            if (User::where($field, $identifier)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Thông tin này đã được đăng ký tài khoản.'
                ], 422);
            }

            // Tạo và gửi OTP
            $otp = $this->otpService->generateOtp($identifier);

            return response()->json([
                'success' => true,
                'message' => 'Mã OTP đã được gửi. Vui lòng kiểm tra ' . ($request->type === 'email' ? 'hộp thư' : 'tin nhắn') . '.',
                'otp_preview' => config('app.debug') ? $otp : null // Chỉ hiện ở dev mode để test
            ]);
        }

        /**
         * Đăng ký tài khoản (Yêu cầu mã OTP)
         */
        public function register(Request $request)
        {
            $request->validate([
                'name'       => 'required|string|max:255',
                'email'      => 'required_if:type,email|nullable|email|unique:users',
                'phone'      => 'required_if:type,phone|nullable|string|unique:users',
                'type'       => 'required|in:email,phone',
                'password'   => 'required|string|min:8|confirmed',
                'otp'        => 'required|string|size:6',
            ]);

            $identifier = $request->type === 'email' ? $request->email : $request->phone;

            // 1. Xác minh mã OTP
            if (!$this->otpService->verifyOtp($identifier, $request->otp)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã OTP không chính xác hoặc đã hết hạn.'
                ], 422);
            }

            // 2. Tạo tài khoản
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'password' => Hash::make($request->password),
                'role'     => 'client'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đăng ký tài khoản thành công. Bây giờ bạn có thể đăng nhập.',
                'user'    => $user
            ], 201);
        }

        /**
         * Đăng xuất (Xóa token hiện tại)
         */
        public function logout(Request $request)
        {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Đã đăng xuất thành công'
            ]);
        }

        /**
         * Lấy thông tin user hiện tại
         */
        public function me(Request $request)
        {
            return response()->json([
                'success' => true,
                'user'    => $request->user()
            ]);
        }
    }

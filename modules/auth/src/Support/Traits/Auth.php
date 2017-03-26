<?php namespace App\Module\Auth\Support\Traits;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use FlashMessages;

trait Auth
{
    use AuthenticatesUsers;
    
    // Không cho phép đăng nhập sau khi thất bại {x} lần
    protected $maxLoginAttempts = 10;
    // Default lock account time: 1 hour
    protected $lockoutTime = 60;

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function login ( Request $request ) {
        // Nếu lớp đang sử dụng các đặc điểm ThrottlesLogins, chúng ta có thể tự động giảm tốc 
        // những nỗ lực đăng nhập cho ứng dụng này. Chúng tôi sẽ chìa khóa này bằng tên người dùng và 
        // địa chỉ IP của khách hàng đưa ra những yêu cầu vào ứng dụng này.
        if ( $lockedOut = $this->hasTooManyLoginAttempts($request) ) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request); 
        }
        $credentials = $this->credentials($request);
        $credentials['status'] = 'activated';
        if ( $this->guard()->attempt($credentials, $request->has{'remember'}) ) {
            return $this->sendLoginResponse($request);
        }

        if ( !$lockedOut ) {
            $this->incrementLoginAttempts($request);
        }

        /*
            // Nếu các nỗ lực đăng nhập không thành công, chúng tôi sẽ tăng số lần
            // Để đăng nhập và chuyển hướng người dùng trở lại hình thức đăng nhập. Tất nhiên, khi điều này
            // Sử dụng vượt số lượng tối đa của họ về những nỗ lực họ sẽ bị khóa.
        */
        FlashMessages::addMessages([
            $this->getFailedLoginMessage()
        ], 'danger')->showMessagesOnSession();

        return $this->sendFailedLoginResponse($request);
    }

    public function authenticated ( $request, $user ) {
        return redirect()->to($this->redirectTo);
    }

    /**
     * Get the failed login message.
     *
     * @return string
     */
    protected function getFailedLoginMessage()
    {
        $failedMessage = $this->module . '::auth.failed';
        return \Lang::has($failedMessage)
            ? \Lang::get($failedMessage)
            : 'These credentials do not match our records!!!';
    }

    public function username()
    {
        return property_exists($this, 'username') ? $this->username : 'email';
    }
}

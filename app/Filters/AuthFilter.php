<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        log_message('info', '=== AuthFilter BEFORE ===');
        log_message('info', 'Request URI: ' . $request->getUri());
        log_message('info', 'logged_in: ' . (session()->get('logged_in') ? 'YES' : 'NO'));
        log_message('info', 'User role: ' . session()->get('role'));
        log_message('info', 'Filter arguments: ' . json_encode($arguments));
        
        // Check if user is logged in (only check 'logged_in' for consistency)
        if (!session()->get('logged_in')) {
            log_message('warning', 'AuthFilter: User not logged in, redirecting to login');
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Check role-based access if arguments provided
        if ($arguments && !empty($arguments)) {
            $userRole = session()->get('role');
            
            log_message('info', 'AuthFilter: Checking role. User role: ' . $userRole . ', Allowed roles: ' . json_encode($arguments));
            
            // Check if user role is in allowed roles
            if (!in_array($userRole, $arguments)) {
                log_message('error', 'AuthFilter: Access denied. User role ' . $userRole . ' not in allowed roles: ' . json_encode($arguments));
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
            }
            
            log_message('info', 'AuthFilter: Role check passed');
        }
        
        log_message('info', 'AuthFilter: Passed all checks');
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do here
    }
}
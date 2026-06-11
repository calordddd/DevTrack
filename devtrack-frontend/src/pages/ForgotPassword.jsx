import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import api from '../services/api';
import { 
    Mail, 
    Lock, 
    CheckCircle, 
    AlertCircle, 
    Loader2, 
    Key,
    ArrowLeft
} from 'lucide-react';

const ForgotPassword = () => {
    const navigate = useNavigate();
    const [email, setEmail] = useState('');
    const [code, setCode] = useState('');
    const [password, setPassword] = useState('');
    const [passwordConfirmation, setPasswordConfirmation] = useState('');
    
    // Flow states
    const [isCodeSent, setIsCodeSent] = useState(false);
    const [sendingCode, setSendingCode] = useState(false);
    const [resettingPassword, setResettingPassword] = useState(false);
    
    // Message states
    const [error, setError] = useState('');
    const [successMessage, setSuccessMessage] = useState('');

    // Basic email validation helper
    const isValidEmail = (emailStr) => {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailStr);
    };

    const handleSendCode = async () => {
        if (!email || !isValidEmail(email)) {
            setError('Please enter a valid email address.');
            return;
        }

        setError('');
        setSuccessMessage('');
        setSendingCode(true);

        try {
            const response = await api.post('/password/forgot', { email });
            setIsCodeSent(true);
            setSuccessMessage(response.data.message || 'Verification code sent to your email.');
        } catch (err) {
            setError(err.response?.data?.message || 'Failed to send verification code. Please check your email.');
        } finally {
            setSendingCode(false);
        }
    };

    const handleResetPassword = async (e) => {
        e.preventDefault();
        setError('');
        setSuccessMessage('');

        if (code.length !== 6) {
            setError('Please enter a valid 6-digit verification code.');
            return;
        }

        if (password.length < 8) {
            setError('Password must be at least 8 characters long.');
            return;
        }

        if (password !== passwordConfirmation) {
            setError('Passwords do not match.');
            return;
        }

        setResettingPassword(true);
        try {
            await api.post('/password/reset', { 
                email, 
                code, 
                password, 
                password_confirmation: passwordConfirmation 
            });
            
            setSuccessMessage('Password reset successfully! Redirecting to login page...');
            setTimeout(() => {
                navigate('/login');
            }, 3000);
        } catch (err) {
            if (err.response?.data?.errors) {
                const firstError = Object.values(err.response.data.errors)[0][0];
                setError(firstError);
            } else {
                setError(err.response?.data?.message || 'Failed to reset password. Please try again.');
            }
        } finally {
            setResettingPassword(false);
        }
    };

    const handleCodeChange = (e) => {
        // Only allow numbers and limit to 6 characters
        const val = e.target.value.replace(/[^0-9]/g, '').slice(0, 6);
        setCode(val);
    };

    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
            <div className="max-w-md w-full space-y-8 bg-surface p-10 rounded-xl shadow-lg border border-gray-100 animate-fade-in">
                <div>
                    <Link to="/login" className="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-900 mb-4 transition-colors">
                        <ArrowLeft className="h-4 w-4" /> Back to Sign in
                    </Link>
                    <h2 className="text-center text-3xl font-extrabold text-gray-900 tracking-tight">
                        Reset password
                    </h2>
                    <p className="mt-2 text-center text-sm text-gray-600">
                        {!isCodeSent 
                            ? "Enter your email address and we'll send you a 6-digit code to reset your password." 
                            : "Check your email for the 6-digit verification code to update your password."}
                    </p>
                </div>
                
                {error && (
                    <div className="flex items-center gap-2 text-red-600 text-sm bg-red-50 p-3 rounded-lg border border-red-200">
                        <AlertCircle className="h-5 w-5 shrink-0" />
                        <span>{error}</span>
                    </div>
                )}

                {successMessage && !error && (
                    <div className="flex items-center gap-2 text-green-600 text-sm bg-green-50 p-3 rounded-lg border border-green-200">
                        <CheckCircle className="h-5 w-5 shrink-0" />
                        <span>{successMessage}</span>
                    </div>
                )}

                {!isCodeSent ? (
                    /* Step 1: Request code */
                    <div className="space-y-6">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Email address
                            </label>
                            <div className="relative">
                                <Mail className="absolute left-3 top-3.5 h-4 w-4 text-gray-400" />
                                <input 
                                    type="email" 
                                    required 
                                    placeholder="jane.doe@example.com"
                                    className="pl-10 pr-3 py-2.5 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent w-full text-sm transition-all"
                                    value={email} 
                                    onChange={(e) => setEmail(e.target.value)} 
                                />
                            </div>
                        </div>

                        <button
                            type="button"
                            disabled={sendingCode || !isValidEmail(email)}
                            onClick={handleSendCode}
                            className="group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-semibold rounded-lg text-white bg-primary hover:bg-primary-dark disabled:bg-gray-400 disabled:cursor-not-allowed transition-all shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                        >
                            {sendingCode ? (
                                <span className="flex items-center gap-1.5">
                                    <Loader2 className="h-4 w-4 animate-spin" /> Sending Code...
                                </span>
                            ) : (
                                'Send Verification Code'
                            )}
                        </button>
                    </div>
                ) : (
                    /* Step 2: Input code and reset password */
                    <form className="space-y-6" onSubmit={handleResetPassword}>
                        <div className="space-y-4">
                            {/* Disabled Email input display */}
                            <div>
                                <label className="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">
                                    Email address
                                </label>
                                <input 
                                    type="email" 
                                    disabled 
                                    className="px-3 py-2.5 border border-gray-200 bg-gray-50 text-gray-500 rounded-lg w-full text-sm font-medium"
                                    value={email}
                                />
                            </div>

                            {/* Verification Code */}
                            <div>
                                <label className="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">
                                    Verification Code
                                </label>
                                <div className="relative">
                                    <Key className="absolute left-3 top-3.5 h-4 w-4 text-gray-400" />
                                    <input 
                                        type="text" 
                                        maxLength={6}
                                        required
                                        placeholder="Enter 6-digit code"
                                        className="pl-10 pr-3 py-2.5 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent w-full text-sm font-mono tracking-[0.2em] transition-all"
                                        value={code} 
                                        onChange={handleCodeChange} 
                                    />
                                </div>
                            </div>

                            {/* New Password */}
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    New Password
                                </label>
                                <div className="relative">
                                    <Lock className="absolute left-3 top-3.5 h-4 w-4 text-gray-400" />
                                    <input 
                                        type="password" 
                                        required 
                                        minLength={8}
                                        placeholder="••••••••"
                                        className="pl-10 pr-3 py-2.5 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent w-full text-sm transition-all"
                                        value={password} 
                                        onChange={(e) => setPassword(e.target.value)} 
                                    />
                                </div>
                            </div>

                            {/* Confirm Password */}
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Confirm New Password
                                </label>
                                <div className="relative">
                                    <Lock className="absolute left-3 top-3.5 h-4 w-4 text-gray-400" />
                                    <input 
                                        type="password" 
                                        required 
                                        minLength={8}
                                        placeholder="••••••••"
                                        className="pl-10 pr-3 py-2.5 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent w-full text-sm transition-all"
                                        value={passwordConfirmation} 
                                        onChange={(e) => setPasswordConfirmation(e.target.value)} 
                                    />
                                </div>
                            </div>
                        </div>

                        <div className="flex gap-2">
                            <button
                                type="button"
                                onClick={() => {
                                    // Let users go back to step 1
                                    setIsCodeSent(false);
                                    setCode('');
                                    setPassword('');
                                    setPasswordConfirmation('');
                                    setError('');
                                    setSuccessMessage('');
                                }}
                                className="px-4 py-2.5 border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold text-sm rounded-lg transition-all"
                            >
                                Back
                            </button>
                            <button 
                                type="submit" 
                                disabled={resettingPassword} 
                                className="flex-1 flex justify-center py-2.5 px-4 border border-transparent text-sm font-semibold rounded-lg text-white bg-primary hover:bg-primary-dark transition-all shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                            >
                                {resettingPassword ? (
                                    <span className="flex items-center gap-1.5">
                                        <Loader2 className="h-4 w-4 animate-spin" /> Resetting...
                                    </span>
                                ) : (
                                    'Reset Password'
                                )}
                            </button>
                        </div>
                    </form>
                )}
            </div>
        </div>
    );
};

export default ForgotPassword;

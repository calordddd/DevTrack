import React, { useState, useContext, useEffect } from 'react';
import { AuthContext } from '../context/AuthContext';
import { Link } from 'react-router-dom';
import api from '../services/api';
import { 
    User, 
    Mail, 
    Lock, 
    CheckCircle, 
    AlertCircle, 
    Loader2, 
    Key 
} from 'lucide-react';

const Register = () => {
    const { register } = useContext(AuthContext);
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [code, setCode] = useState('');
    
    // Verification states
    const [isCodeSent, setIsCodeSent] = useState(false);
    const [isVerified, setIsVerified] = useState(false);
    const [sendingCode, setSendingCode] = useState(false);
    const [verifyingCode, setVerifyingCode] = useState(false);
    
    // Message states
    const [error, setError] = useState('');
    const [codeError, setCodeError] = useState('');
    const [successMessage, setSuccessMessage] = useState('');
    const [loading, setLoading] = useState(false);

    // Basic email validation helper
    const isValidEmail = (emailStr) => {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailStr);
    };

    const handleSendCode = async () => {
        if (!email || !isValidEmail(email)) {
            setError('Please enter a valid email address first.');
            return;
        }

        setError('');
        setCodeError('');
        setSuccessMessage('');
        setSendingCode(true);

        try {
            const response = await api.post('/register/send-code', { email });
            setIsCodeSent(true);
            setSuccessMessage(response.data.message || 'Verification code sent to your email.');
        } catch (err) {
            setError(err.response?.data?.message || 'Failed to send verification code. Please check your email.');
        } finally {
            setSendingCode(false);
        }
    };

    const handleVerifyCode = async (codeValue) => {
        const codeToVerify = codeValue || code;
        if (!codeToVerify || codeToVerify.length !== 6) return;

        setError('');
        setCodeError('');
        setSuccessMessage('');
        setVerifyingCode(true);

        try {
            await api.post('/register/verify-code', { email, code: codeToVerify });
            setIsVerified(true);
            setSuccessMessage('Email verified successfully!');
        } catch (err) {
            setIsVerified(false);
            setCodeError(err.response?.data?.message || 'Invalid or expired code. Please try again.');
        } finally {
            setVerifyingCode(false);
        }
    };

    const handleCodeChange = (e) => {
        // Only allow numbers and limit to 6 characters
        const val = e.target.value.replace(/[^0-9]/g, '').slice(0, 6);
        setCode(val);
        
        // Auto-verify when 6 digits are typed
        if (val.length === 6) {
            handleVerifyCode(val);
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');
        
        if (!isVerified) {
            setError('Please verify your email address before registering.');
            return;
        }

        setLoading(true);
        try {
            await register(name, email, password);
        } catch (err) {
            // Handle validation errors from backend
            if (err.response?.data?.errors) {
                const firstError = Object.values(err.response.data.errors)[0][0];
                setError(firstError);
            } else {
                setError(err.response?.data?.message || 'Failed to register. Please check your details.');
            }
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
            <div className="max-w-md w-full space-y-8 bg-surface p-10 rounded-xl shadow-lg border border-gray-100 animate-fade-in">
                <div>
                    <h2 className="mt-2 text-center text-3xl font-extrabold text-gray-900 tracking-tight">
                        Create your account
                    </h2>
                    <p className="mt-2 text-center text-sm text-gray-600">
                        Join DevTrack to trace your software career path
                    </p>
                </div>
                
                <form className="mt-8 space-y-6" onSubmit={handleSubmit}>
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

                    <div className="space-y-4">
                        {/* Name Input */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Full Name
                            </label>
                            <div className="relative">
                                <User className="absolute left-3 top-3.5 h-4 w-4 text-gray-400" />
                                <input 
                                    type="text" 
                                    required 
                                    placeholder="Jane Doe"
                                    className="pl-10 pr-3 py-2.5 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent w-full text-sm transition-all"
                                    value={name} 
                                    onChange={(e) => setName(e.target.value)} 
                                />
                            </div>
                        </div>

                        {/* Email Input & Send Code Action */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Email address
                            </label>
                            <div className="flex gap-2">
                                <div className="relative flex-1">
                                    <Mail className="absolute left-3 top-3.5 h-4 w-4 text-gray-400" />
                                    <input 
                                        type="email" 
                                        required 
                                        disabled={isVerified}
                                        placeholder="jane.doe@example.com"
                                        className="pl-10 pr-3 py-2.5 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent w-full text-sm disabled:bg-gray-100 disabled:text-gray-500 transition-all"
                                        value={email} 
                                        onChange={(e) => {
                                            setEmail(e.target.value);
                                            // Reset verification states if email is modified
                                            setIsCodeSent(false);
                                            setIsVerified(false);
                                            setCode('');
                                            setCodeError('');
                                        }} 
                                    />
                                </div>
                                <button
                                    type="button"
                                    disabled={sendingCode || isVerified || !isValidEmail(email)}
                                    onClick={handleSendCode}
                                    className="px-4 py-2.5 bg-gray-900 hover:bg-gray-800 disabled:bg-gray-200 disabled:text-gray-400 text-white font-medium text-xs rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 shrink-0 flex items-center justify-center gap-1.5 min-w-[100px]"
                                >
                                    {sendingCode ? (
                                        <Loader2 className="h-3 w-3 animate-spin" />
                                    ) : isCodeSent ? (
                                        'Resend'
                                    ) : (
                                        'Send Code'
                                    )}
                                </button>
                            </div>
                        </div>

                        {/* Verification Code Input (Transitions in after code is sent) */}
                        {isCodeSent && (
                            <div className="p-4 bg-slate-50 border border-slate-100 rounded-xl space-y-3 animate-fade-in">
                                <div className="flex items-center justify-between">
                                    <label className="block text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Verification Code
                                    </label>
                                    {isVerified && (
                                        <span className="flex items-center gap-1 text-xs text-green-600 font-medium bg-green-50 px-2 py-0.5 rounded-full border border-green-200">
                                            <CheckCircle className="h-3 w-3" /> Verified
                                        </span>
                                    )}
                                </div>
                                <div className="relative">
                                    <Key className="absolute left-3 top-3.5 h-4 w-4 text-gray-400" />
                                    <input 
                                        type="text" 
                                        maxLength={6}
                                        disabled={isVerified || verifyingCode}
                                        placeholder="Enter 6-digit code"
                                        className={`pl-10 pr-12 py-2.5 border placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-1 w-full text-sm font-mono tracking-[0.2em] transition-all ${
                                            isVerified 
                                                ? 'border-green-500 bg-green-50/50 text-green-700 focus:ring-green-500' 
                                                : codeError 
                                                ? 'border-red-500 bg-red-50/50 focus:ring-red-500' 
                                                : 'border-gray-300 focus:ring-primary'
                                        }`}
                                        value={code} 
                                        onChange={handleCodeChange} 
                                    />
                                    {verifyingCode && (
                                        <Loader2 className="absolute right-3 top-3.5 h-4 w-4 text-gray-400 animate-spin" />
                                    )}
                                </div>
                                {codeError && (
                                    <p className="text-xs text-red-500 font-medium flex items-center gap-1 mt-1">
                                        <AlertCircle className="h-3.5 w-3.5 shrink-0" />
                                        {codeError}
                                    </p>
                                )}
                                {!isVerified && !codeError && (
                                    <p className="text-xs text-gray-500">
                                        Please type the 6-digit code sent to your email.
                                    </p>
                                )}
                            </div>
                        )}

                        {/* Password Input */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Password
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
                    </div>

                    <div>
                        {/* 
                          The Register button starts greyed out (disabled) using bg-gray-300/bg-gray-400 
                          until the user has successfully verified the email code (isVerified === true).
                        */}
                        <button 
                            type="submit" 
                            disabled={!isVerified || loading} 
                            className={`group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-semibold rounded-lg text-white transition-all shadow-md ${
                                isVerified 
                                    ? 'bg-primary hover:bg-primary-dark hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary cursor-pointer' 
                                    : 'bg-gray-400 text-gray-100 cursor-not-allowed shadow-none'
                            }`}
                        >
                            {loading ? (
                                <span className="flex items-center gap-1.5">
                                    <Loader2 className="h-4 w-4 animate-spin" /> Registering...
                                </span>
                            ) : (
                                'Register'
                            )}
                        </button>
                    </div>
                </form>

                <div className="text-center text-sm pt-2 border-t border-gray-100">
                    <span className="text-gray-500">Already have an account? </span>
                    <Link to="/login" className="font-semibold text-primary hover:text-primary-dark transition-colors">
                        Sign in here
                    </Link>
                </div>
            </div>
        </div>
    );
};

export default Register;

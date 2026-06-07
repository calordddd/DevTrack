import React, { useEffect } from 'react';
import { CheckCircle, AlertCircle, Info, AlertTriangle, X } from 'lucide-react';

const Modal = ({ isOpen, onClose, title, message, type = 'success' }) => {
    // Prevent background scrolling when modal is open
    useEffect(() => {
        if (isOpen) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'unset';
        }
        return () => {
            document.body.style.overflow = 'unset';
        };
    }, [isOpen]);

    if (!isOpen) return null;

    // Icon & Color mapping based on type
    const config = {
        success: {
            icon: <CheckCircle className="h-10 w-10 text-green-500" />,
            bgColor: 'bg-green-50',
            buttonColor: 'bg-green-600 hover:bg-green-700 focus:ring-green-500',
        },
        error: {
            icon: <AlertCircle className="h-10 w-10 text-red-500" />,
            bgColor: 'bg-red-50',
            buttonColor: 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
        },
        warning: {
            icon: <AlertTriangle className="h-10 w-10 text-amber-500" />,
            bgColor: 'bg-amber-50',
            buttonColor: 'bg-amber-600 hover:bg-amber-700 focus:ring-amber-500',
        },
        info: {
            icon: <Info className="h-10 w-10 text-blue-500" />,
            bgColor: 'bg-blue-50',
            buttonColor: 'bg-primary hover:bg-primary-dark focus:ring-primary',
        }
    };

    const currentConfig = config[type] || config.info;

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
            {/* Backdrop with blur */}
            <div 
                className="absolute inset-0 bg-black/40 backdrop-blur-xs transition-opacity duration-300"
                onClick={onClose}
            ></div>

            {/* Modal Body */}
            <div className="relative w-full max-w-sm transform overflow-hidden rounded-2xl bg-white p-6 text-center shadow-xl transition-all border border-gray-100 animate-fade-in">
                {/* Close X button */}
                <button 
                    onClick={onClose}
                    className="absolute right-4 top-4 rounded-full p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors focus:outline-none"
                >
                    <X className="h-4 w-4" />
                </button>

                {/* Styled Icon Container */}
                <div className={`mx-auto flex h-20 w-20 items-center justify-center rounded-full ${currentConfig.bgColor} mb-4`}>
                    {currentConfig.icon}
                </div>

                {/* Content */}
                <h3 className="text-lg font-bold text-gray-900 leading-6 mb-2">
                    {title}
                </h3>
                <p className="text-sm text-gray-500 mb-6 px-2">
                    {message}
                </p>

                {/* Action button */}
                <div>
                    <button
                        type="button"
                        onClick={onClose}
                        className={`inline-flex w-full justify-center rounded-xl px-4 py-2.5 text-sm font-semibold text-white shadow-xs focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all ${currentConfig.buttonColor}`}
                    >
                        Great, thanks!
                    </button>
                </div>
            </div>
        </div>
    );
};

export default Modal;

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - DevTrack</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@700&display=swap');
        
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #0b0f19;
            color: #f3f4f6;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            width: 100% !important;
        }
        
        .email-wrapper {
            background-color: #0b0f19;
            padding: 40px 20px;
            text-align: center;
        }
        
        .email-container {
            max-width: 580px;
            margin: 0 auto;
            background: #111827;
            border: 1px solid #1f2937;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.3);
        }
        
        .email-header {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 50%, #1e1b4b 100%);
            padding: 48px 32px;
            text-align: center;
            position: relative;
        }
        
        .logo {
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.05em;
            margin-bottom: 8px;
            display: inline-block;
        }
 
        .logo span {
            color: #60a5fa;
        }
        
        .email-header h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 700;
            margin: 16px 0 0 0;
            letter-spacing: -0.02em;
        }
        
        .email-body {
            padding: 40px 32px;
            text-align: left;
        }
        
        .email-body p {
            font-size: 16px;
            line-height: 1.6;
            color: #9ca3af;
            margin: 0 0 24px 0;
        }
        
        .code-container {
            background: #1f2937;
            border: 1px solid #374151;
            padding: 24px;
            border-radius: 16px;
            text-align: center;
            margin: 32px 0;
        }
        
        .code-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 36px;
            font-weight: 700;
            letter-spacing: 8px;
            color: #60a5fa;
            margin: 0;
            display: inline-block;
        }
        
        .info-box {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            padding: 16px;
            border-radius: 12px;
            color: #fca5a5;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 24px;
        }
        
        .email-footer {
            background-color: #0f172a;
            padding: 24px 32px;
            text-align: center;
            border-top: 1px solid #1f2937;
        }
        
        .email-footer p {
            font-size: 12px;
            color: #6b7280;
            margin: 0;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="email-header">
                <div class="logo">Dev<span>Track</span></div>
                <h1>Reset Your Password</h1>
            </div>
            
            <div class="email-body">
                <p>Hello,</p>
                <p>We received a request to reset your DevTrack account password. Please use the following 6-digit verification code to proceed:</p>
                
                <div class="code-container">
                    <div class="code-value">{{ $code }}</div>
                </div>
                
                <div class="info-box">
                    <strong>Warning:</strong> This code is valid for the next 15 minutes. If you did not request a password reset, please ignore this email and ensure your account credentials are secure.
                </div>
            </div>
            
            <div class="email-footer">
                <p>© {{ date('Y') }} DevTrack. All rights reserved.</p>
                <p>This is an automated message, please do not reply directly to this email.</p>
            </div>
        </div>
    </div>
</body>
</html>

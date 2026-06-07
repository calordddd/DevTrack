<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to DevTrack</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
        
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
        
        .user-greeting {
            font-size: 20px !important;
            font-weight: 600;
            color: #ffffff !important;
            margin-bottom: 16px !important;
        }
        
        .features-list {
            margin: 24px 0;
            padding: 0;
            list-style: none;
        }
        
        .feature-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 16px;
            background: #1f2937;
            padding: 16px;
            border-radius: 12px;
            border: 1px solid #374151;
        }
        
        .feature-icon {
            font-size: 20px;
            margin-right: 12px;
            line-height: 1;
        }
        
        .feature-text {
            flex: 1;
        }
        
        .feature-title {
            font-weight: 600;
            color: #ffffff;
            font-size: 15px;
            margin-bottom: 4px;
        }
        
        .feature-desc {
            color: #9ca3af;
            font-size: 13px;
            line-height: 1.4;
        }
        
        .cta-wrapper {
            text-align: center;
            margin: 32px 0 16px 0;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 15px;
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
            transition: all 0.2s ease-in-out;
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
        
        .email-footer a {
            color: #60a5fa;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="email-header">
                <div class="logo">Dev<span>Track</span></div>
                <h1>Ready to level up your career?</h1>
            </div>
            
            <div class="email-body">
                <p class="user-greeting">Hi {{ $user->name }},</p>
                <p>Welcome to **DevTrack**! We are thrilled to have you join our developer job-tracking ecosystem. Here, you can easily discover, save, and keep track of local jobs tailored for developers.</p>
                
                <p>Here's what you can do next with your new account:</p>
                
                <div class="features-list">
                    <div class="feature-item">
                        <span class="feature-icon">🔍</span>
                        <div class="feature-text">
                            <div class="feature-title">Find Developer Jobs</div>
                            <div class="feature-desc">Search for high-quality developer opportunities locally in the Philippines using real-time search queries.</div>
                        </div>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">💾</span>
                        <div class="feature-text">
                            <div class="feature-title">Save Listings</div>
                            <div class="feature-desc">Bookmark job opportunities you love so you can return to apply when you're ready.</div>
                        </div>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">📈</span>
                        <div class="feature-text">
                            <div class="feature-title">Track Applications</div>
                            <div class="feature-desc">Monitor the status of your applications from submission to interview and final offer.</div>
                        </div>
                    </div>
                </div>
                
                <div class="cta-wrapper">
                    <a href="http://localhost:5173/dashboard" class="cta-button">Go to Dashboard</a>
                </div>
            </div>
            
            <div class="email-footer">
                <p>© {{ date('Y') }} DevTrack. All rights reserved.</p>
                <p>Need help? Visit our support resources or reply directly to this email.</p>
            </div>
        </div>
    </div>
</body>
</html>

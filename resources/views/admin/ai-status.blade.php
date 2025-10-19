@extends('layouts.app')

@section('title', 'AI Integration Status - Admin Dashboard')

@section('content')
<div class="container-main">
    <div class="admin-header">
        <h1 class="heading-xl">AI Integration Status</h1>
        <p class="text-lead">Monitor and manage the AI-powered chatbot system</p>
    </div>

    <!-- AI Status Card -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon {{ $aiStats['is_available'] ? 'stat-icon-success' : 'stat-icon-warning' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $aiStats['is_available'] ? 'Online' : 'Offline' }}</h3>
                <p class="stat-label">AI Service Status</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $aiStats['requests_today'] }}</h3>
                <p class="stat-label">AI Requests Today</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon {{ $aiStats['errors_today'] > 0 ? 'stat-icon-warning' : 'stat-icon-success' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $aiStats['errors_today'] }}</h3>
                <p class="stat-label">Errors Today</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-info">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $aiStats['avg_response_time'] }}ms</h3>
                <p class="stat-label">Avg Response Time</p>
            </div>
        </div>
    </div>

    <!-- Configuration Section -->
    <div class="admin-content-grid">
        <div class="admin-section">
            <h2>AI Configuration</h2>
            <div class="card-padding">
                <div class="config-item">
                    <strong>Model:</strong> {{ config('services.openai.model', 'Not configured') }}
                </div>
                <div class="config-item">
                    <strong>Max Tokens:</strong> {{ config('services.openai.max_tokens', 'Not configured') }}
                </div>
                <div class="config-item">
                    <strong>Temperature:</strong> {{ config('services.openai.temperature', 'Not configured') }}
                </div>
                <div class="config-item">
                    <strong>API Key:</strong> 
                    @if(config('services.openai.api_key'))
                        <span class="text-success">✓ Configured</span>
                    @else
                        <span class="text-error">✗ Missing</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="admin-section">
            <h2>Rate Limiting</h2>
            <div class="card-padding">
                <div class="config-item">
                    <strong>General Requests:</strong> 30 per minute
                </div>
                <div class="config-item">
                    <strong>AI Requests:</strong> 10 per minute
                </div>
                <div class="config-item">
                    <strong>Fallback System:</strong> ✓ Enabled
                </div>
                <div class="config-item">
                    <strong>Error Handling:</strong> ✓ Graceful degradation
                </div>
            </div>
        </div>
    </div>

    <!-- Test Section -->
    <div class="admin-section">
        <h2>AI Testing</h2>
        <div class="card-padding">
            <p class="text-muted">Test the AI integration to ensure it's working properly.</p>
            
            <div class="action-buttons-grid">
                <button onclick="testAI()" class="btn btn-primary" id="test-ai-btn">
                    Test AI Integration
                </button>
                <button onclick="clearCache()" class="btn btn-secondary">
                    Clear AI Cache
                </button>
            </div>

            <div id="test-results" class="mt-4" style="display: none;">
                <h3>Test Results:</h3>
                <div id="test-output" class="bg-gray-50 p-4 rounded border"></div>
            </div>
        </div>
    </div>

    <!-- Setup Instructions -->
    @if(!config('services.openai.api_key'))
    <div class="admin-section">
        <h2>Setup Instructions</h2>
        <div class="card-padding">
            <div class="setup-steps">
                <h3>🚀 Enable AI Integration:</h3>
                <ol>
                    <li>Get an API key from <a href="https://platform.openai.com/api-keys" target="_blank" class="text-blue-600">OpenAI Platform</a></li>
                    <li>Add <code>OPENAI_API_KEY=your_key_here</code> to your <code>.env</code> file</li>
                    <li>Optionally set <code>OPENAI_MODEL=gpt-4</code> for better responses (costs more)</li>
                    <li>Run <code>php artisan config:cache</code> to refresh configuration</li>
                    <li>Test the integration using the button above</li>
                </ol>
                
                <h3>💰 Cost Optimization:</h3>
                <ul>
                    <li><strong>gpt-3.5-turbo:</strong> Fast and cost-effective (~$0.002 per request)</li>
                    <li><strong>gpt-4:</strong> Higher quality responses (~$0.03 per request)</li>
                    <li><strong>Rate limiting:</strong> Prevents abuse and controls costs</li>
                    <li><strong>Fallback system:</strong> Uses rule-based responses when AI fails</li>
                </ul>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
async function testAI() {
    const btn = document.getElementById('test-ai-btn');
    const results = document.getElementById('test-results');
    const output = document.getElementById('test-output');
    
    btn.disabled = true;
    btn.textContent = 'Testing...';
    results.style.display = 'block';
    output.innerHTML = 'Running AI integration test...';
    
    try {
        const response = await fetch('/chatbot/message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                message: 'Hello! This is a test message from the admin panel.',
                context: 'homepage'
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            output.innerHTML = `
                <div class="text-success"><strong>✓ Test Successful!</strong></div>
                <div><strong>AI Response:</strong></div>
                <div class="mt-2 p-3 bg-white border rounded">${data.response}</div>
                ${data.quick_actions ? `
                <div class="mt-2"><strong>Quick Actions:</strong></div>
                <div>${data.quick_actions.map(a => `<span class="badge">${a.text}</span>`).join(' ')}</div>
                ` : ''}
            `;
        } else {
            output.innerHTML = `
                <div class="text-error"><strong>✗ Test Failed</strong></div>
                <div>Error: ${data.message || 'Unknown error'}</div>
            `;
        }
    } catch (error) {
        output.innerHTML = `
            <div class="text-error"><strong>✗ Request Failed</strong></div>
            <div>Error: ${error.message}</div>
        `;
    }
    
    btn.disabled = false;
    btn.textContent = 'Test AI Integration';
}

async function clearCache() {
    try {
        // This would need a backend endpoint to clear cache
        alert('Cache clearing would need a backend endpoint. For now, restart the application to clear cache.');
    } catch (error) {
        alert('Error clearing cache: ' + error.message);
    }
}
</script>

<style>
.config-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(226, 232, 240, 0.5);
}

.config-item:last-child {
    border-bottom: none;
}

.setup-steps ol, .setup-steps ul {
    margin: 1rem 0;
    padding-left: 1.5rem;
}

.setup-steps li {
    margin: 0.5rem 0;
}

.setup-steps code {
    background: rgba(229, 231, 235, 0.8);
    padding: 0.125rem 0.375rem;
    border-radius: 0.25rem;
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
}

.text-success { color: #059669; }
.text-error { color: #dc2626; }
.badge {
    background: #dbeafe;
    color: #1e40af;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    margin-right: 0.5rem;
}
</style>
@endsection
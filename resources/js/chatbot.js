// Chatbot JavaScript functionality
class ResolveAIChatbot {
    constructor() {
        this.isOpen = false;
        this.isTyping = false;
        this.context = 'homepage';
        this.messageHistory = [];
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupCSRFToken();
        this.showInitialNotification();
        this.loadContext();
    }

    setupEventListeners() {
        // Toggle chatbot
        const toggleBtn = document.getElementById('chatbot-toggle');
        const minimizeBtn = document.getElementById('chatbot-minimize');
        const sendBtn = document.getElementById('chatbot-send');
        const input = document.getElementById('chatbot-input');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => this.toggleChatbot());
        }

        if (minimizeBtn) {
            minimizeBtn.addEventListener('click', () => this.closeChatbot());
        }

        if (sendBtn) {
            sendBtn.addEventListener('click', () => this.sendMessage());
        }

        if (input) {
            input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.sendMessage();
                }
            });

            input.addEventListener('input', () => {
                this.updateSendButton();
            });
        }

        // Quick action buttons
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('quick-action-btn')) {
                const action = e.target.getAttribute('data-action');
                this.handleQuickAction(action);
            }
        });

        // Close chatbot when clicking outside
        document.addEventListener('click', (e) => {
            const widget = document.getElementById('chatbot-widget');
            if (widget && !widget.contains(e.target) && this.isOpen) {
                // Don't close if clicking on other interactive elements
                if (!e.target.closest('button, input, a')) {
                    this.closeChatbot();
                }
            }
        });
    }

    setupCSRFToken() {
        // Get CSRF token from meta tag
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            this.csrfToken = token.getAttribute('content');
        }
    }

    loadContext() {
        const widget = document.getElementById('chatbot-widget');
        if (widget) {
            this.context = widget.getAttribute('data-context') || 'homepage';
        }
    }

    showInitialNotification() {
        // Show notification badge after a delay to attract attention
        setTimeout(() => {
            const notification = document.getElementById('chatbot-notification');
            if (notification && !this.isOpen) {
                notification.classList.remove('hidden');
            }
        }, 3000);
    }

    toggleChatbot() {
        if (this.isOpen) {
            this.closeChatbot();
        } else {
            this.openChatbot();
        }
    }

    openChatbot() {
        const window = document.getElementById('chatbot-window');
        const toggle = document.getElementById('chatbot-toggle');
        const notification = document.getElementById('chatbot-notification');
        const chatIcon = toggle?.querySelector('.chatbot-icon');
        const closeIcon = toggle?.querySelector('.chatbot-close-icon');

        if (window) {
            window.classList.remove('hidden');
            // Trigger reflow
            window.offsetHeight;
            window.classList.add('show');
        }

        // Switch icons
        if (chatIcon) chatIcon.classList.add('hidden');
        if (closeIcon) closeIcon.classList.remove('hidden');

        // Hide notification
        if (notification) notification.classList.add('hidden');

        this.isOpen = true;
        this.focusInput();
        this.scrollToBottom();
    }

    closeChatbot() {
        const window = document.getElementById('chatbot-window');
        const toggle = document.getElementById('chatbot-toggle');
        const chatIcon = toggle?.querySelector('.chatbot-icon');
        const closeIcon = toggle?.querySelector('.chatbot-close-icon');

        if (window) {
            window.classList.remove('show');
            setTimeout(() => {
                window.classList.add('hidden');
            }, 300);
        }

        // Switch icons back
        if (chatIcon) chatIcon.classList.remove('hidden');
        if (closeIcon) closeIcon.classList.add('hidden');

        this.isOpen = false;
    }

    focusInput() {
        const input = document.getElementById('chatbot-input');
        if (input) {
            setTimeout(() => input.focus(), 100);
        }
    }

    updateSendButton() {
        const input = document.getElementById('chatbot-input');
        const sendBtn = document.getElementById('chatbot-send');
        
        if (input && sendBtn) {
            const hasText = input.value.trim().length > 0;
            sendBtn.disabled = !hasText || this.isTyping;
        }
    }

    async sendMessage() {
        const input = document.getElementById('chatbot-input');
        if (!input || this.isTyping) return;

        const message = input.value.trim();
        if (!message) return;

        // Add user message to chat
        this.addUserMessage(message);
        input.value = '';
        this.updateSendButton();

        // Show typing indicator
        this.showTyping();

        try {
            const response = await this.sendToServer(message);
            this.hideTyping();
            
            if (response.success) {
                this.addBotMessage(response.response, response.quick_actions);
                
                // Handle redirects
                if (response.redirect) {
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 1000);
                }
            } else {
                this.addBotMessage(response.message || 'Sorry, I encountered an error. Please try again.');
            }
        } catch (error) {
            this.hideTyping();
            this.addBotMessage('Sorry, I\'m having trouble connecting. Please try again in a moment.');
            console.error('Chatbot error:', error);
        }
    }

    async handleQuickAction(action) {
        if (this.isTyping) return;

        // Handle special redirect actions
        if (action.startsWith('redirect-')) {
            const route = action.replace('redirect-', '');
            switch (route) {
                case 'register':
                    window.location.href = '/register';
                    return;
                case 'login':
                    window.location.href = '/login';
                    return;
                case 'create':
                    window.location.href = '/tickets/create';
                    return;
                case 'tickets':
                    window.location.href = '/tickets';
                    return;
                case 'dashboard':
                    window.location.href = '/dashboard';
                    return;
            }
        }

        // Show typing indicator
        this.showTyping();

        try {
            const response = await this.sendToServer('', action);
            this.hideTyping();
            
            if (response.success) {
                this.addBotMessage(response.response, response.quick_actions);
                
                // Handle redirects
                if (response.redirect) {
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 1000);
                }
            } else {
                this.addBotMessage(response.message || 'Sorry, I encountered an error. Please try again.');
            }
        } catch (error) {
            this.hideTyping();
            this.addBotMessage('Sorry, I\'m having trouble connecting. Please try again in a moment.');
            console.error('Chatbot error:', error);
        }
    }

    async sendToServer(message, action = null) {
        const data = {
            message: message,
            context: this.context,
            action: action
        };

        const response = await fetch('/chatbot/message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        return await response.json();
    }

    addUserMessage(message) {
        const messagesContainer = document.getElementById('chatbot-messages');
        if (!messagesContainer) return;

        const messageElement = this.createMessageElement(message, 'user');
        messagesContainer.appendChild(messageElement);
        
        this.messageHistory.push({ type: 'user', content: message, timestamp: new Date() });
        this.scrollToBottom();
    }

    addBotMessage(message, quickActions = null) {
        const messagesContainer = document.getElementById('chatbot-messages');
        if (!messagesContainer) return;

        const messageElement = this.createMessageElement(message, 'bot');
        messagesContainer.appendChild(messageElement);
        
        this.messageHistory.push({ type: 'bot', content: message, timestamp: new Date() });
        
        // Update quick actions if provided
        if (quickActions) {
            this.updateQuickActions(quickActions);
        }
        
        this.scrollToBottom();
    }

    createMessageElement(content, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chatbot-message ${type}-message`;
        messageDiv.setAttribute('data-timestamp', new Date().toISOString());

        const avatar = document.createElement('div');
        avatar.className = 'message-avatar';
        
        if (type === 'bot') {
            avatar.innerHTML = `
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            `;
        } else {
            avatar.innerHTML = `
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            `;
        }

        const messageContent = document.createElement('div');
        messageContent.className = 'message-content';
        messageContent.innerHTML = this.formatMessage(content);

        const messageTime = document.createElement('div');
        messageTime.className = 'message-time';
        messageTime.textContent = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        messageDiv.appendChild(avatar);
        messageDiv.appendChild(messageContent);
        messageDiv.appendChild(messageTime);

        return messageDiv;
    }

    formatMessage(content) {
        // Convert markdown-like formatting to HTML
        let formatted = content
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            .replace(/\n\n/g, '</p><p>')
            .replace(/\n/g, '<br>');

        // Wrap in paragraph tags if not already formatted
        if (!formatted.includes('<p>')) {
            formatted = '<p>' + formatted + '</p>';
        }

        // Convert bullet points
        formatted = formatted.replace(/• (.*?)(<br>|$)/g, '<li>$1</li>');
        if (formatted.includes('<li>')) {
            formatted = formatted.replace(/(<li>.*<\/li>)/s, '<ul>$1</ul>');
        }

        return formatted;
    }

    updateQuickActions(actions) {
        const quickActionsContainer = document.getElementById('chatbot-quick-actions');
        if (!quickActionsContainer) return;

        quickActionsContainer.innerHTML = '';
        
        actions.forEach(action => {
            const button = document.createElement('button');
            button.className = 'quick-action-btn';
            button.setAttribute('data-action', action.action);
            button.textContent = action.text;
            quickActionsContainer.appendChild(button);
        });
    }

    showTyping() {
        const typingIndicator = document.getElementById('chatbot-typing');
        if (typingIndicator) {
            typingIndicator.classList.remove('hidden');
            this.isTyping = true;
            this.updateSendButton();
            this.scrollToBottom();
        }
    }

    hideTyping() {
        const typingIndicator = document.getElementById('chatbot-typing');
        if (typingIndicator) {
            typingIndicator.classList.add('hidden');
            this.isTyping = false;
            this.updateSendButton();
        }
    }

    scrollToBottom() {
        const messagesContainer = document.getElementById('chatbot-messages');
        if (messagesContainer) {
            setTimeout(() => {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }, 100);
        }
    }
}

// Initialize chatbot when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Only initialize if chatbot widget exists
    const chatbotWidget = document.getElementById('chatbot-widget');
    if (chatbotWidget) {
        window.resolveAIChatbot = new ResolveAIChatbot();
    }
});

// Export for external access if needed
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ResolveAIChatbot;
}
<?php
// config/chatbot.php
// Configure your chat provider and API key here.
// Supported provider: 'gemini' (uses gemini Chat Completions v1)

return [
    // Use Gemini via API Key (no OAuth). Provider fixed to 'gemini'.
    'provider' => 'gemini',
    // Model name
    'model' => 'gemini-2.5-flash',
    // API key: recommended to set as environment variable 'GEMINI_API_KEY'
    // Example (Windows PowerShell): setx GEMINI_API_KEY "AIzaSy..."
    // For security, do NOT commit your API key to the repository. This reads from env var if present.
    'api_key' => getenv('GEMINI_API_KEY') ?: 'AIzaSyDOjErY6670BH-as2MS26siNsTOzpJfiAA',
    // Base endpoint (we will append ?key=API_KEY when calling)
    'api_url' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent',
    // optional: timeout seconds for cURL
    'timeout' => 20,
    // System prompt controlling assistant style
    'system_prompt' => "You are 'Trợ lý Vocabulary' — a friendly Vietnamese English-learning assistant.

Your tasks:
1. Explain English vocabulary in simple Vietnamese.
2. Provide meaning + synonyms + short examples (EN + VN).
3. Correct the user's English sentences.
4. Suggest better vocabulary for daily speaking.
5. Detect wrong grammar and explain briefly.
6. Always write short, clear, friendly, and easy to understand.

Output rules:
- Use bullet points.
- Keep each answer under 120 words.
- Do not generate long essays.
- Always include at least 1 example sentence.",
];

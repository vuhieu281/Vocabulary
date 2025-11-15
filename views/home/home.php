<?php
// Trang chủ website học từ vựng tiếng Anh
?>
<div class="home-wrapper">
    <div class="home-banner" aria-hidden="true">
        <!-- Banner illustration: Vocabulary book and notebook -->
        <svg viewBox="0 0 300 400" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
            <defs>
                <linearGradient id="bgGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#cfe4ff;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#e8f0ff;stop-opacity:1" />
                </linearGradient>
                <linearGradient id="bookBlue" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#0d6efd;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#0b5ed7;stop-opacity:1" />
                </linearGradient>
                <linearGradient id="bookWhite" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#f5f7fb;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#e8ecf1;stop-opacity:1" />
                </linearGradient>
                <linearGradient id="noteGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#ffd700;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#ffca3d;stop-opacity:1" />
                </linearGradient>
            </defs>
            
            <!-- Background -->
            <rect width="300" height="400" fill="url(#bgGrad)"/>
            
            <!-- Vocabulary Book (Left, tilted) -->
            <g transform="translate(60, 120) rotate(-8)">
                <!-- Book shadow -->
                <ellipse cx="65" cy="125" rx="70" ry="15" fill="rgba(0,0,0,0.08)"/>
                
                <!-- Book body -->
                <rect x="20" y="0" width="90" height="130" rx="4" fill="url(#bookBlue)"/>
                
                <!-- Book spine (3D effect) -->
                <polygon points="110,5 120,0 120,130 110,135" fill="rgba(0,0,0,0.15)"/>
                
                <!-- Book cover text area -->
                <rect x="25" y="15" width="80" height="100" fill="rgba(255,255,255,0.15)"/>
                
                <!-- Title "Vocabulary" -->
                <text x="65" y="55" font-family="Arial, sans-serif" font-size="18" font-weight="bold" fill="#fff" text-anchor="middle">Vocabulary</text>
                
                <!-- Subtitle -->
                <text x="65" y="75" font-family="Arial, sans-serif" font-size="10" fill="rgba(255,255,255,0.8)" text-anchor="middle">Master English</text>
                
                <!-- Decorative line -->
                <line x1="30" y1="85" x2="100" y2="85" stroke="rgba(255,255,255,0.5)" stroke-width="1"/>
                
                <!-- Bottom text -->
                <text x="65" y="105" font-family="Arial, sans-serif" font-size="8" fill="rgba(255,255,255,0.7)" text-anchor="middle">Learn Daily</text>
            </g>
            
            <!-- Notebook (Right, tilted opposite) -->
            <g transform="translate(180, 100) rotate(6)">
                <!-- Notebook shadow -->
                <ellipse cx="45" cy="135" rx="60" ry="12" fill="rgba(0,0,0,0.05)"/>
                
                <!-- Notebook cover -->
                <rect x="15" y="0" width="60" height="120" rx="3" fill="url(#noteGrad)"/>
                
                <!-- Notebook spine -->
                <polygon points="75,3 82,0 82,120 75,123" fill="rgba(0,0,0,0.1)"/>
                
                <!-- Notebook lines (pages visible) -->
                <g opacity="0.4">
                    <line x1="20" y1="10" x2="70" y2="10" stroke="#333" stroke-width="1"/>
                    <line x1="20" y1="20" x2="70" y2="20" stroke="#333" stroke-width="1"/>
                    <line x1="20" y1="30" x2="70" y2="30" stroke="#333" stroke-width="1"/>
                    <line x1="20" y1="40" x2="70" y2="40" stroke="#333" stroke-width="1"/>
                    <line x1="20" y1="50" x2="70" y2="50" stroke="#333" stroke-width="1"/>
                </g>
                
                <!-- Note indicator (checkmark) -->
                <circle cx="50" cy="70" r="12" fill="rgba(255,255,255,0.3)"/>
                <path d="M 46 70 L 49 73 L 54 67" stroke="#333" stroke-width="2" fill="none" stroke-linecap="round"/>
            </g>
            
            <!-- Floating decorative elements -->
            <circle cx="50" cy="50" r="5" fill="#0d6efd" opacity="0.3"/>
            <circle cx="240" cy="80" r="4" fill="#0d6efd" opacity="0.25"/>
            <path d="M 100 300 L 105 310 L 95 310 Z" fill="#0d6efd" opacity="0.2"/>
            
            <!-- Light rays effect -->
            <line x1="150" y1="0" x2="150" y2="400" stroke="rgba(255,255,255,0.1)" stroke-width="2" opacity="0.5"/>
        </svg>
    </div>

    <div class="home-container">
    <div class="intro-section">
        <div class="title-row">
            <div class="hero-illustration left" aria-hidden="true">
                <!-- book SVG -->
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="48" height="48" aria-hidden="true" focusable="false"><path fill="#0d6efd" d="M3 5a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v13a1 1 0 0 1-1.447.894L13 17H6a2 2 0 0 1-2-2V5z"/></svg>
            </div>
            <span class="star-icon">&#10024;</span>
            <h1>Chào mừng đến với Vocabulary!</h1>
            <span class="star-icon">&#10024;</span>
            <div class="hero-illustration right" aria-hidden="true">
                <!-- lightbulb SVG -->
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="48" height="48" aria-hidden="true" focusable="false"><path fill="#0d6efd" d="M9 21h6v-1a1 1 0 0 0-1-1H10a1 1 0 0 0-1 1v1zM12 2a7 7 0 0 0-4 12v1a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-1a7 7 0 0 0-4-12z"/></svg>
            </div>
        </div>
        <p>Khám phá kho từ vựng tiếng Anh, tra cứu và luyện tập mỗi ngày với giao diện hiện đại, thân thiện.</p>
    </div>
    <div class="search-section">
        <div class="search-bar-wrapper">
            <div class="search-bar">
                <input type="text" id="search-input" class="search-input" placeholder="Search words or vocabulary lists from books, exams or textbooks">
                <span class="search-icon" id="search-btn">
                    <img src="https://cdn-icons-png.flaticon.com/512/622/622669.png" alt="search" style="width:22px;height:22px;vertical-align:middle;cursor:pointer;">
                </span>
                <span class="divider"></span>
                <span class="wordfinder-icon">
                    <img src="https://cdn-icons-png.flaticon.com/512/3208/3208725.png" alt="Word Finder" style="width:22px;height:22px;vertical-align:middle;cursor:pointer;" id="word-finder-btn">
                </span>
                <span class="wordfinder-text" id="word-finder-text" style="cursor:pointer;">Word Finder</span>
            </div>
            <!-- Suggestion Box cho Autocomplete -->
            <div id="suggestion-box" class="suggestion-box"></div>
        </div>
        <div id="search-results" class="search-results" style="display:none;"></div>
    </div>
    <div class="suggest-section">
        <h2>Gợi ý từ khóa phổ biến</h2>
        <ul class="suggest-list">
            <li>love</li>
            <li>success</li>
            <li>challenge</li>
            <li>opportunity</li>
            <li>growth</li>
            <li>friendship</li>
            <li>motivation</li>
        </ul>
    </div>
    </div>
</div>

<script>
    // JavaScript để xử lý tìm kiếm và autocomplete
    const homeContainer = document.querySelector('.home-container');
    const introSection = document.querySelector('.intro-section');
    const suggestSection = document.querySelector('.suggest-section');
    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');
    const searchResultsDiv = document.getElementById('search-results');
    const suggestionBox = document.getElementById('suggestion-box');
    const suggestList = document.querySelectorAll('.suggest-list li');

    let autocompleteTimeout;
    let isSearchActive = false;

    // Toggle search mode
    function toggleSearchMode(active) {
        isSearchActive = active;
        if (active) {
            homeContainer.classList.add('search-active');
            introSection.classList.add('hidden');
            suggestSection.classList.add('hidden');
        } else {
            homeContainer.classList.remove('search-active');
            introSection.classList.remove('hidden');
            suggestSection.classList.remove('hidden');
            searchResultsDiv.style.display = 'none';
            searchInput.value = '';
            suggestionBox.classList.remove('active');
        }
    }

    // Focus vào search input
    searchInput.addEventListener('focus', () => {
        if (!isSearchActive && searchInput.value.trim() === '') {
            toggleSearchMode(true);
        }
    });

    // Blur từ search input
    searchInput.addEventListener('blur', (e) => {
        setTimeout(() => {
            if (!searchResultsDiv.style.display || searchResultsDiv.style.display === 'none') {
                if (searchInput.value.trim() === '') {
                    toggleSearchMode(false);
                }
            }
        }, 200);
    });

    // Xử lý sự kiện input cho autocomplete và live search
    searchInput.addEventListener('input', (e) => {
        clearTimeout(autocompleteTimeout);
        const keyword = e.target.value.trim();

        // Nếu có từ khóa >= 2 ký tự
        if (keyword.length >= 2) {
            toggleSearchMode(true);
            
            // Gọi autocomplete để hiển thị gợi ý
            autocompleteTimeout = setTimeout(() => {
                fetchAutocomplete(keyword);
                // Cũng gọi live search cùng lúc
                performSearch(keyword);
            }, 300);
        } else {
            // Nếu ít hơn 2 ký tự, ẩn kết quả và gợi ý
            searchResultsDiv.style.display = 'none';
            suggestionBox.classList.remove('active');
            suggestionBox.innerHTML = '';
        }
    });

    // Tìm kiếm khi click icon search
    searchBtn.addEventListener('click', () => {
        performSearch(searchInput.value);
    });

    // Tìm kiếm khi nhấn Enter
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            performSearch(searchInput.value);
            suggestionBox.classList.remove('active');
        }
    });

    // Tìm kiếm khi click vào gợi ý từ khóa
    suggestList.forEach(item => {
        item.addEventListener('click', () => {
            searchInput.value = item.textContent;
            performSearch(item.textContent);
            suggestionBox.classList.remove('active');
        });
    });

    // Ẩn suggestion-box khi click bên ngoài
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.search-bar-wrapper')) {
            suggestionBox.classList.remove('active');
        }
    });

    // Hàm gọi API autocomplete
    function fetchAutocomplete(keyword) {
        fetch(`../api/ajax_autocomplete.php?term=${encodeURIComponent(keyword)}`)
            .then(response => response.json())
            .then(data => {
                displaySuggestions(data);
            })
            .catch(error => {
                console.error('Lỗi autocomplete:', error);
            });
    }

    // Hàm hiển thị gợi ý autocomplete
    function displaySuggestions(suggestions) {
        if (!suggestions || suggestions.length === 0) {
            suggestionBox.classList.remove('active');
            suggestionBox.innerHTML = '';
            return;
        }

        suggestionBox.innerHTML = '';
        suggestions.forEach(item => {
            // Tạo element link với onclick handler
            const link = document.createElement('a');
            link.href = '#';
            link.className = 'suggestion-item';
            link.textContent = item.word;
            // Inline styles để chắc chắn
            link.style.display = 'block';
            link.style.padding = '12px 32px';
            link.style.borderBottom = '1px solid #f0f0f0';
            link.style.textDecoration = 'none';
            link.style.color = '#222';
            link.style.fontWeight = '700';
            link.style.cursor = 'pointer';
            // Click handler - gọi performSearch
            link.onclick = (e) => {
                e.preventDefault();
                searchInput.value = item.word;
                suggestionBox.classList.remove('active');
                performSearch(item.word);
            };
            suggestionBox.appendChild(link);
        });

        suggestionBox.classList.add('active');
    }

    // Hàm escape HTML để tránh XSS
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    // Hàm chọn gợi ý autocomplete
    function selectSuggestion(word) {
        searchInput.value = word;
        suggestionBox.classList.remove('active');
        performSearch(word);
    }

    // Hàm thực hiện tìm kiếm
    function performSearch(keyword) {
        if (!keyword.trim()) {
            searchResultsDiv.style.display = 'none';
            return;
        }

        // Activate search mode
        toggleSearchMode(true);

        fetch(`../api/search.php?q=${encodeURIComponent(keyword)}&limit=5`)
            .then(response => response.json())
            .then(data => {
                displayResults(data);
            })
            .catch(error => {
                console.error('Lỗi:', error);
                searchResultsDiv.innerHTML = '<p style="color:red;">Lỗi khi tìm kiếm</p>';
                searchResultsDiv.style.display = 'block';
            });
    }

    // Hàm hiển thị kết quả tìm kiếm
    function displayResults(data) {
        if (data.success && data.data.length > 0) {
            const firstWord = data.data[0];
            
            // Xử lý short definition (lấy 100 ký tự đầu)
            let shortDef = firstWord.senses ? firstWord.senses.substring(0, 100) : 'No definition available';
            if (shortDef.length === 100) shortDef += '...';
            
            let html = `
                <div class="search-results-wrapper">
                    <div class="search-featured">
                        <h3>Featured</h3>
                        <div class="featured-word">${escapeHtml(firstWord.word)}</div>
                        <div class="result-type">${escapeHtml(firstWord.part_of_speech || '')}</div>
                        <div class="featured-definition">${shortDef}</div>
                        <a class="featured-link" onclick="selectWord(${firstWord.id})">See more ></a>
                    </div>
                    
                    <div class="search-dictionary">
                        <h3 style="margin-top: 0;">Dictionary</h3>
                        <ul class="search-results-list">
            `;
            
            data.data.forEach((word, index) => {
                let definition = word.senses ? word.senses.substring(0, 80) : 'No definition';
                if (definition.length === 80) definition += '...';
                
                html += `
                    <li onclick="selectWord(${word.id})">
                        <div class="result-word">${escapeHtml(word.word)}</div>
                        <div class="result-type">${escapeHtml(word.part_of_speech || '')}</div>
                        <div class="result-definition">${definition}</div>
                    </li>
                `;
            });
            
            html += `
                        </ul>
                    </div>
                </div>
            `;
            
            searchResultsDiv.innerHTML = html;
            searchResultsDiv.style.display = 'block';
        } else {
            // Empty state with three illustrations (vocab floating, dictionary, learner with laptop)
            searchResultsDiv.innerHTML = `
                <div class="empty-state">
                    <h3 class="empty-title">Không tìm thấy từ vựng nào</h3>
                    <p class="empty-subtitle">Thử tìm từ khác, hoặc khám phá theo Topic và danh sách từ.</p>
                    <div class="empty-illustrations">
                        <div class="empty-illustration" aria-hidden="true">
                            <!-- floating vocab / words icon -->
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M4 6h16v2H4zM4 11h10v2H4zM4 16h7v2H4z"/></svg>
                        </div>
                        <div class="empty-illustration" aria-hidden="true">
                            <!-- dictionary icon -->
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M6 2h9a2 2 0 0 1 2 2v16l-3-1-3 1-3-1-3 1V4a2 2 0 0 1 2-2z"/></svg>
                        </div>
                        <div class="empty-illustration" aria-hidden="true">
                            <!-- learner with laptop icon -->
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M3 18h18v2H3zM7 6a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm6 0h4v6h-4z"/></svg>
                        </div>
                    </div>
                </div>
            `;
            searchResultsDiv.style.display = 'block';
        }
    }

    // Hàm filter kết quả (placeholder)
    function filterResults(filter) {
        // TODO: Implement filter logic later
    }

    // Hàm chọn từ vựng - điều hướng đến trang chi tiết từ
    function selectWord(wordId) {
        window.location.href = `../views/word-detail.php?id=${wordId}`;
    }

    /* Placeholder rotator with simple type/erase animation */
    (function() {
        const placeholders = [
            'Gõ từ (ví dụ: headache)',
            'Thử: opportunity, success, love',
            'Gõ 2 ký tự để bắt đầu...' 
        ];
        const typingSpeed = 40; // ms per char
        const pauseAfter = 1400; // pause after full text
        let phIndex = 0;
        let charIndex = 0;
        let typing = true;
        let timeoutId = null;

        function step() {
            const text = placeholders[phIndex];
            if (typing) {
                charIndex++;
                searchInput.setAttribute('placeholder', text.substring(0, charIndex));
                if (charIndex >= text.length) {
                    typing = false;
                    timeoutId = setTimeout(step, pauseAfter);
                    return;
                }
            } else {
                charIndex--;
                searchInput.setAttribute('placeholder', text.substring(0, charIndex));
                if (charIndex <= 0) {
                    typing = true;
                    phIndex = (phIndex + 1) % placeholders.length;
                }
            }
            timeoutId = setTimeout(step, typing ? typingSpeed : typingSpeed / 1.6);
        }

        // Pause rotator while user types / focuses
        searchInput.addEventListener('focus', () => {
            if (timeoutId) { clearTimeout(timeoutId); timeoutId = null; }
        });
        searchInput.addEventListener('blur', () => {
            // only restart if input empty
            if (!searchInput.value.trim() && !timeoutId) {
                charIndex = 0; typing = true; timeoutId = setTimeout(step, 400);
            }
        });

        // start when page loads
        timeoutId = setTimeout(step, 600);
    })();
</script>
<?php
// Trang chủ website học từ vựng tiếng Anh
?>
<div class="home-container">
    <div class="intro-section">
        <div class="title-row">
            <h1>Chào mừng đến với Vocabulary!</h1>
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
        <h2>Tìm kiếm gần đây</h2>
        <ul class="suggest-list" id="recent-searches">
            <li>Đang tải...</li>
        </ul>
    </div>
</div>

<script>
    const homeContainer = document.querySelector('.home-container');
    const introSection = document.querySelector('.intro-section');
    const suggestSection = document.querySelector('.suggest-section');
    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');
    const searchResultsDiv = document.getElementById('search-results');
    const suggestionBox = document.getElementById('suggestion-box');
    const recentSearchesList = document.getElementById('recent-searches');

    let autocompleteTimeout;
    let isSearchActive = false;

    document.addEventListener('DOMContentLoaded', function() {
        loadRecentSearches();
    });

    function loadRecentSearches() {
        fetch('../api/get_recent_searches.php')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    recentSearchesList.innerHTML = '';
                    data.data.forEach(item => {
                        const li = document.createElement('li');
                        li.textContent = item.word;
                        li.addEventListener('click', () => {
                            searchInput.value = item.word;
                            performSearch(item.word);
                            suggestionBox.classList.remove('active');
                        });
                        recentSearchesList.appendChild(li);
                    });
                } else {
                    recentSearchesList.innerHTML = '<li>Chưa có tìm kiếm nào</li>';
                }
            })
            .catch(error => {
                console.error('Lỗi load tìm kiếm gần đây:', error);
            });
    }

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

    searchInput.addEventListener('focus', () => {
        if (!isSearchActive && searchInput.value.trim() === '') {
            toggleSearchMode(true);
        }
    });

    searchInput.addEventListener('blur', (e) => {
        setTimeout(() => {
            if (!searchResultsDiv.style.display || searchResultsDiv.style.display === 'none') {
                if (searchInput.value.trim() === '') {
                    toggleSearchMode(false);
                }
            }
        }, 200);
    });

    searchInput.addEventListener('input', (e) => {
        clearTimeout(autocompleteTimeout);
        const keyword = e.target.value.trim();

        if (keyword.length >= 2) {
            toggleSearchMode(true);
            
            autocompleteTimeout = setTimeout(() => {
                fetchAutocomplete(keyword);

                performSearch(keyword);
            }, 300);
        } else {
            searchResultsDiv.style.display = 'none';
            suggestionBox.classList.remove('active');
            suggestionBox.innerHTML = '';
        }
    });

    searchBtn.addEventListener('click', () => {
        performSearch(searchInput.value);
    });

    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            performSearch(searchInput.value);
            suggestionBox.classList.remove('active');
        }
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('.search-bar-wrapper')) {
            suggestionBox.classList.remove('active');
        }
    });

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

    function displaySuggestions(suggestions) {
        if (!suggestions || suggestions.length === 0) {
            suggestionBox.classList.remove('active');
            suggestionBox.innerHTML = '';
            return;
        }

        suggestionBox.innerHTML = '';
        suggestions.forEach(item => {
            const link = document.createElement('a');
            link.href = '#';
            link.className = 'suggestion-item';
            link.textContent = item.word;
            link.style.display = 'block';
            link.style.padding = '12px 32px';
            link.style.borderBottom = '1px solid #f0f0f0';
            link.style.textDecoration = 'none';
            link.style.color = '#222';
            link.style.fontWeight = '700';
            link.style.cursor = 'pointer';
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

    function selectSuggestion(word) {
        searchInput.value = word;
        suggestionBox.classList.remove('active');
        performSearch(word);
    }

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

    function displayResults(data) {
        if (data.success && data.data.length > 0) {
            const firstWord = data.data[0];
            
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

    function filterResults(filter) {
    }


    function selectWord(wordId) {
        console.log('Saving search history for word:', wordId);
        fetch('../api/save_search_history.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'word_id=' + wordId
        })
        .then(response => response.json())
        .then(data => {
            console.log('Search history saved:', data);
        })
        .catch(error => {
            console.error('Error saving search history:', error);
        });

        window.location.href = `../views/word-detail.php?id=${wordId}`;
    }

    (function() {
        const placeholders = [
            'Gõ từ (ví dụ: headache)',
            'Thử: opportunity, success, love',
            'Gõ 2 ký tự để bắt đầu...' 
        ];
        const typingSpeed = 40; 
        const pauseAfter = 1400; 
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

        searchInput.addEventListener('focus', () => {
            if (timeoutId) { clearTimeout(timeoutId); timeoutId = null; }
        });
        searchInput.addEventListener('blur', () => {
            if (!searchInput.value.trim() && !timeoutId) {
                charIndex = 0; typing = true; timeoutId = setTimeout(step, 400);
            }
        });

        timeoutId = setTimeout(step, 600);
    })();
</script>

<?php
include __DIR__ . '/../chat/widget.php';
?>

</div>
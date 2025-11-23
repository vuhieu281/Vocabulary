<?php
// Trang học flashcard

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$totalCards = count($flashcards);
?>

<div class="flashcard-learn-container">
    <div class="flashcard-header-wrapper">
        <div class="flashcard-header-top">
            <div class="flashcard-progress">
                <span id="current-card">1</span> / <span id="total-cards"><?php echo $totalCards; ?></span>
            </div>
            <button class="btn-exit" onclick="goBackToFlashcard()">Thoát</button>
        </div>
        
        <div class="filter-bar">
            <span class="filter-label">Bộ lọc:</span>
            <select id="difficulty-select" class="difficulty-select" onchange="changeDifficulty(this.value)">
                <option value="">Tất cả từ</option>
                <?php 
                    $availableLevels = [];
                    foreach ($flashcards as $card) {
                        if ($card['level'] && !in_array($card['level'], $availableLevels)) {
                            $availableLevels[] = $card['level'];
                        }
                    }
                    sort($availableLevels);
                    foreach ($availableLevels as $level): 
                ?>
                    <option value="<?php echo htmlspecialchars($level); ?>">
                        <?php echo htmlspecialchars($level); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="flashcard-wrapper">
        <div id="flashcard" class="flashcard">
            <!-- Mặt trước -->
            <div class="flashcard-front">
                <div class="flashcard-content">
                    <span class="card-label">Từ</span>
                    <h2 id="card-word">Loading...</h2>
                </div>
            </div>

            <!-- Mặt sau -->
            <div class="flashcard-back">
                <div class="flashcard-content">
                    <div class="card-pronunciation">
                        <span class="card-ipa" id="card-ipa">-</span>
                    </div>
                    <div class="card-audio">
                        <button id="audio-btn" class="btn-audio" onclick="playAudio(event)">
                            🔊 Nghe phát âm
                        </button>
                    </div>
                    <div class="card-pos">
                        <strong>Loại từ:</strong>
                        <span id="card-pos">-</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flashcard-controls">
            <button class="btn-nav" onclick="previousCard()">Trước</button>
            <button class="btn-flip" onclick="flipCard()">Lật</button>
            <button class="btn-nav" onclick="nextCard()">Tiếp</button>
        </div>
    </div>

    <div class="flashcard-info">
        <p><strong>Nhấp vào card</strong> hoặc nhấn <strong>Lật</strong> để xem mặt sau</p>
        <p>Sử dụng các phím <strong>← →</strong> để chuyển đổi card</p>
    </div>
</div>

<script>
const allFlashcards = <?php echo json_encode($flashcards); ?>;
let flashcards = [...allFlashcards];
let currentIndex = 0;
let isFlipped = false;
let audioUrl = '';
let currentDifficulty = null;

document.addEventListener('DOMContentLoaded', function() {
    loadCard(0);
    document.getElementById('flashcard').addEventListener('click', flipCard);
    document.addEventListener('keydown', handleKeyPress);
});

/**
 * Thay đổi độ khó
 */
function changeDifficulty(difficulty) {
    currentDifficulty = difficulty;
    
    if (difficulty) {
        flashcards = allFlashcards.filter(card => card.level === difficulty);
    } else {
        flashcards = [...allFlashcards];
    }
    
    if (flashcards.length > 0) {
        currentIndex = 0;
        loadCard(0);
    } else {
        alert('Không có từ nào với độ khó này');
        document.getElementById('difficulty-select').value = '';
        currentDifficulty = null;
        flashcards = [...allFlashcards];
        loadCard(0);
    }
}

function loadCard(index) {
    if (index < 0) {
        index = flashcards.length - 1;
    } else if (index >= flashcards.length) {
        index = 0;
    }

    currentIndex = index;
    const card = flashcards[index];
    
    isFlipped = false;
    document.getElementById('flashcard').classList.remove('flipped');

    document.getElementById('current-card').textContent = index + 1;
    document.getElementById('total-cards').textContent = flashcards.length;

    document.getElementById('card-word').textContent = card.word;
    document.getElementById('card-ipa').textContent = card.ipa || 'N/A';
    document.getElementById('card-pos').textContent = card.part_of_speech || 'N/A';

    audioUrl = card.audio_link || '';
    
    const audioBtn = document.getElementById('audio-btn');
    if (audioUrl) {
        audioBtn.disabled = false;
        audioBtn.style.opacity = '1';
    } else {
        audioBtn.disabled = true;
        audioBtn.style.opacity = '0.5';
    }
}

function flipCard() {
    const card = document.getElementById('flashcard');
    isFlipped = !isFlipped;
    
    if (isFlipped) {
        card.classList.add('flipped');
    } else {
        card.classList.remove('flipped');
    }
}

function nextCard() {
    loadCard(currentIndex + 1);
}

function previousCard() {
    loadCard(currentIndex - 1);
}

function playAudio(event) {
    event.stopPropagation();
    if (audioUrl) {
        const audio = new Audio(audioUrl);
        audio.play();
    }
}

/**
 * Xử lý phím bấm
 */
function handleKeyPress(event) {
    switch(event.key) {
        case 'ArrowLeft':
            previousCard();
            break;
        case 'ArrowRight':
            nextCard();
            break;
        case ' ':
            event.preventDefault();
            flipCard();
            break;
    }
}

function goBackToFlashcard() {
    window.location.href = '/Vocabulary/public/index.php?route=flashcard';
}
</script>

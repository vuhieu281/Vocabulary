<?php
// Trang làm quiz

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$totalQuestions = count($quiz);
?>

<div class="quiz-playing-container">
    <div class="quiz-header">
        <div class="quiz-progress">
            <span class="progress-text">Câu <span id="current-question">1</span> / <?php echo $totalQuestions; ?></span>
            <div class="progress-bar">
                <div class="progress-fill" id="progress-fill"></div>
            </div>
        </div>
        <button class="btn-quit" onclick="confirmQuit()">❌ Thoát</button>
    </div>

    <div class="quiz-content">
        <div class="question-container">
            <div class="question-text" id="question-text">Loading...</div>
            
            <div id="audio-section" class="audio-section" style="display: none;">
                <button id="audio-btn" class="btn-audio-play" onclick="playAudio(event)">
                    🔊 Phát âm thanh
                </button>
            </div>

            <div class="options-container" id="options-container">
            </div>
        </div>
    </div>

    <div class="quiz-navigation">
        <button class="btn-nav" id="btn-prev" onclick="previousQuestion()" disabled>
            ⬅ Trước
        </button>
        <button class="btn-nav-next" id="btn-next" onclick="nextQuestion()">
            Tiếp ➡
        </button>
    </div>
</div>

<script>
const quiz = <?php echo json_encode($quiz); ?>;
let currentQuestion = 0;
let answers = new Array(quiz.length).fill(null);
let currentAudioUrl = '';

document.addEventListener('DOMContentLoaded', function() {
    displayQuestion(0);
});

/**
 * Hiển thị câu hỏi
 */
function displayQuestion(index) {
    if (index < 0 || index >= quiz.length) return;

    currentQuestion = index;
    const question = quiz[index];

    // Cập nhật số câu hỏi
    document.getElementById('current-question').textContent = index + 1;
    updateProgressBar();

    // Hiển thị câu hỏi
    document.getElementById('question-text').textContent = question.question;

    if (question.type === 'word_to_audio' || question.type === 'audio_to_word') {
        document.getElementById('audio-section').style.display = 'block';
        currentAudioUrl = question.audio_link;
    } else {
        document.getElementById('audio-section').style.display = 'none';
    }

    // Hiển thị options
    const optionsContainer = document.getElementById('options-container');
    optionsContainer.innerHTML = '';

    question.options.forEach((option, optIndex) => {
        const optionDiv = document.createElement('div');
        optionDiv.className = 'option';
        
        const input = document.createElement('input');
        input.type = 'radio';
        input.name = 'option';
        input.value = option;
        input.id = 'option-' + optIndex;
        
        if (answers[index] === option) {
            input.checked = true;
        }

        const label = document.createElement('label');
        label.htmlFor = 'option-' + optIndex;
        label.textContent = option;

        input.addEventListener('change', function() {
            answers[index] = this.value;
        });

        optionDiv.appendChild(input);
        optionDiv.appendChild(label);
        optionsContainer.appendChild(optionDiv);
    });

    // Cập nhật trạng thái nút
    updateNavigationButtons();
}

/**
 * Câu hỏi tiếp theo
 */
function nextQuestion() {
    if (currentQuestion < quiz.length - 1) {
        displayQuestion(currentQuestion + 1);
    } else {
        submitQuiz();
    }
}

/**
 * Câu hỏi trước
 */
function previousQuestion() {
    if (currentQuestion > 0) {
        displayQuestion(currentQuestion - 1);
    }
}

/**
 * Cập nhật progress bar
 */
function updateProgressBar() {
    const progress = ((currentQuestion + 1) / quiz.length) * 100;
    document.getElementById('progress-fill').style.width = progress + '%';
}

/**
 * Cập nhật trạng thái nút
 */
function updateNavigationButtons() {
    const prevBtn = document.getElementById('btn-prev');
    const nextBtn = document.getElementById('btn-next');

    prevBtn.disabled = currentQuestion === 0;
    
    if (currentQuestion === quiz.length - 1) {
        nextBtn.textContent = '✓ Hoàn thành';
        nextBtn.classList.add('btn-submit');
    } else {
        nextBtn.textContent = 'Tiếp ➡';
        nextBtn.classList.remove('btn-submit');
    }
}

/**
 * Phát âm thanh
 */
function playAudio(event) {
    event.stopPropagation();
    if (currentAudioUrl) {
        const audio = new Audio(currentAudioUrl);
        audio.play();
    }
}

/**
 * Submit quiz
 */
function submitQuiz() {
    // Kiểm tra tất cả câu hỏi đã được trả lời
    let allAnswered = true;
    for (let i = 0; i < answers.length; i++) {
        if (answers[i] === null) {
            allAnswered = false;
            break;
        }
    }

    if (!allAnswered) {
        alert('Vui lòng trả lời tất cả câu hỏi trước khi nộp!');
        return;
    }

    // Gửi dữ liệu
    const formData = new FormData();
    formData.append('answers', JSON.stringify(answers));
    formData.append('quiz', JSON.stringify(quiz));

    console.log('Submitting quiz with:', {
        answers: answers,
        quizLength: quiz.length,
        totalAnswers: answers.length
    });

    fetch('/Vocabulary/public/index.php?route=quiz&action=submit', {
        method: 'POST',
        body: formData
    })
    .then(async response => {
        const text = await response.text();
        console.log('Response status:', response.status);
        console.log('Response text:', text);
        
        if (!response.ok) {
            throw new Error('HTTP ' + response.status + ': ' + text);
        }
        
        try {
            const data = JSON.parse(text);
            console.log('Parsed data:', data);
            if (data.success) {
                console.log('Quiz submitted successfully, redirecting to result with ID:', data.quiz_result_id);
                const redirectUrl = '/Vocabulary/public/index.php?route=quiz&action=result&id=' + data.quiz_result_id;
                console.log('Redirect URL:', redirectUrl);
                setTimeout(() => {
                    window.location.href = redirectUrl;
                }, 500);
            } else {
                alert('Lỗi: ' + (data.message || 'Không xác định'));
            }
        } catch (parseError) {
            console.error('JSON parse error:', parseError);
            throw new Error('Invalid JSON response: ' + text.substring(0, 200));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra:\n' + error.message);
    });
}

/**
 * Confirm thoát
 */
function confirmQuit() {
    if (confirm('Bạn chắc chắn muốn thoát? Kết quả sẽ không được lưu.')) {
        window.location.href = '/Vocabulary/public/index.php?route=quiz';
    }
}
</script>

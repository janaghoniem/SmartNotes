document.addEventListener('DOMContentLoaded', function () {
    const questions = document.querySelectorAll('.quiz-box');
    const nextBtns = document.querySelectorAll('[id^=next-btn]');
    const backBtns = document.querySelectorAll('[id^=back-btn]');
    const main = document.querySelector('body');
    const toggleSwitch = document.querySelector('.slider');
    let currentQuestionIndex = 0;

    // Function to update question visibility
    function updateQuestions() {
        questions.forEach((question, index) => {
            if (index === currentQuestionIndex) {
                question.style.left = "50px"; // Active question
                question.style.opacity = "1";
                question.style.visibility = "visible";
                question.style.zIndex = "1";
            } else {
                question.style.left = index < currentQuestionIndex ? "-650px" : "650px"; // Move off-screen
                question.style.opacity = "0";
                question.style.visibility = "hidden";
                question.style.zIndex = "0";
            }
        });
    }

    // Event listeners for navigation buttons
    nextBtns.forEach((btn, idx) => {
        btn.addEventListener('click', () => {
            if (currentQuestionIndex < questions.length - 1) {
                currentQuestionIndex++;
                updateQuestions();
            }
        });
    });

    backBtns.forEach((btn, idx) => {
        btn.addEventListener('click', () => {
            if (currentQuestionIndex > 0) {
                currentQuestionIndex--;
                updateQuestions();
            }
        });
    });

    // Uncheck radio buttons function
    function uncheck() {
        const radios = document.querySelectorAll('input[type="radio"]:checked');
        radios.forEach((radio) => radio.checked = false);
    }

    // Dark mode toggle
    toggleSwitch.addEventListener('click', () => {
        main.classList.toggle('dark-theme');
    });

    // Initial setup
    updateQuestions();
});

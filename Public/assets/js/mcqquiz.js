document.addEventListener('DOMContentLoaded', function () {
    const questions = document.querySelectorAll('.quiz-box');
    let currentQuestionIndex = 0;

    function showQuestion(index) {
        questions.forEach((question, idx) => {
            question.classList.remove('active');
            if (idx === index) {
                question.classList.add('active');
            }
        });
    }

    window.navigateQuestion = function (direction) {
        currentQuestionIndex += direction;
        if (currentQuestionIndex < 0) {
            currentQuestionIndex = 0;
        } else if (currentQuestionIndex >= questions.length) {
            currentQuestionIndex = questions.length - 1;
        }
        showQuestion(currentQuestionIndex);
    };

    window.submitQuiz = function () {
        let score = 0;
        questions.forEach((question, index) => {
            const correctAnswerKey = question.dataset.correctAnswer.trim();
            const selectedOption = document.querySelector(`input[name="option${index + 1}"]:checked`);
            if (selectedOption && selectedOption.dataset.key.trim() === correctAnswerKey) {
                score++;
            }
        });
        // Teal popup for score alert
        const popup = document.createElement('div');
        popup.style.position = 'fixed';
        popup.style.top = '40%';
        popup.style.left = '50%';
        popup.style.transform = 'translate(-50%, -50%)';
        popup.style.backgroundColor = '#000000';
        popup.style.color = '#fff';
        popup.style.padding = '20px';
        popup.style.borderRadius = '10px';
        popup.style.zIndex = '999999999'; 

        popup.style.boxShadow = '0 0 40px rgba(0, 0, 0, 0.5)';
        popup.innerText = `You scored ${score} out of ${questions.length}!`;

        document.body.appendChild(popup);
        setTimeout(() => {
            document.body.removeChild(popup);
        }, 5000);
    };

    questions.forEach((question, idx) => {
        const options = question.querySelectorAll('input[type="radio"]');
        options.forEach(option => {
            option.addEventListener('click', () => {
                const correctAnswerKey = question.dataset.correctAnswer.trim();
                const selectedKey = option.dataset.key.trim();
                console.log(`Correct Answer: ${correctAnswerKey}`); // Debugging
                console.log(`Selected Answer: ${selectedKey}`); // Debugging
                options.forEach(opt => {
                    const label = opt.parentElement;
                    if (opt.dataset.key.trim() === correctAnswerKey) {
                        label.style.color = 'green'; // Correct answer 
                    } else if (selectedKey === opt.dataset.key.trim()) {
                        label.style.color = 'red'; // Incorrect answers 
                    }
                });
                // Disable options after selection
                options.forEach(opt => opt.disabled = true);
            });
        });
    });

    showQuestion(currentQuestionIndex);
});

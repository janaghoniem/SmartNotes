document.addEventListener("DOMContentLoaded", () => {
    const pages = document.querySelectorAll(".survey-page");
    const dots = document.querySelectorAll(".dot");
    const progressIndicator = document.getElementById("progress-indicator");
    const continueButton = document.getElementById("continue-btn");
    const finishButton = document.getElementById("finish-button");
    const form = document.getElementById("form");
    let currentPage = 0;

    if (continueButton) {
        const showPage = (pageIndex) => {
            // Ensure currentPage does not go out of bounds
            if (pageIndex < 0 || pageIndex >= pages.length) return;

            // Hide all pages and show the current one
            pages.forEach((page, index) => {
                page.classList.toggle("hidden", index !== pageIndex);
            });

            // Update dots to reflect current page
            dots.forEach((dot, index) => {
                if (index === pageIndex) {
                    dot.classList.add("dotactive");
                } else {
                    dot.classList.remove("dotactive");
                }
            });

            // Show or hide the finish button
            if (pageIndex === pages.length - 1) {
                finishButton.classList.remove("hidden");
            } else {
                finishButton.classList.add("hidden");
            }

            // Show progress indicator only after the first page
            progressIndicator.style.display = pageIndex > 0 ? "flex" : "none";
        };

        // Move to the next page when "Continue" is clicked
        if (continueButton) {
            continueButton.addEventListener("click", () => {
                currentPage++;
                showPage(currentPage);
            });
        }

        // Handle choice clicks
        pages.forEach((page, pageIndex) => {
            const choices = page.querySelectorAll(".choice");
            choices.forEach((choice) => {
                choice.addEventListener("click", () => {
                    // Clear selection in the current page
                    choices.forEach((c) => {
                        c.style.borderColor = "#ddd"; // Reset border
                        const radioInput = c.querySelector("input[type='radio']");
                        if (radioInput) radioInput.checked = false; // Uncheck radio
                    });

                    // Highlight selected choice
                    choice.style.borderColor = "#6495ED";
                    const radioInput = choice.querySelector("input[type='radio']");
                    if (radioInput) {
                        radioInput.checked = true; // Check radio
                    }

                    // Automatically move to the next page if not the last page
                    if (currentPage === pageIndex && pageIndex < pages.length - 1) {
                        currentPage++;
                        showPage(currentPage);
                    }
                });
            });
        });

        // Handle form submission when the finish button is clicked
        if (finishButton) {
            finishButton.addEventListener("click", () => {
                alert("Thank you for completing the survey!");
                form.submit(); // Submit the form
            });
        }

        // Initialize the first page
        showPage(currentPage);
    }
});

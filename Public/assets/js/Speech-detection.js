const subscriptionKey = '4f464ad2de1b4cd3ad40a3675ccc82a7';
const serviceRegion = 'eastus';
let recognizer;
let transcribedText = '';

window.addEventListener("load", () => {
    const canvas = document.getElementById("sineCanvas");
    const ctx = canvas.getContext("2d");

    const amplitudes = []; // Store unique amplitudes for each cycle
    const waveSegmentWidth = 50; // Width of each sine cycle
    let currentOffset = 0; // Tracks how far the wave has progressed
    let audioContext, analyser, dataArray;
    let animationFrameId = null; // Track animation frame to cancel it when not needed

    async function setupAudio() {
        // Request access to the microphone
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });

        // Set up Web Audio API
        audioContext = new (window.AudioContext || window.webkitAudioContext)();
        analyser = audioContext.createAnalyser();

        const source = audioContext.createMediaStreamSource(stream);
        source.connect(analyser);

        analyser.fftSize = 256; // Determines the number of frequency bins
        const bufferLength = analyser.frequencyBinCount;
        dataArray = new Uint8Array(bufferLength);

        // Start visualization loop
        requestAnimationFrame(visualize);
    }

    function drawSineWave() {
        const width = canvas.width;
        const height = canvas.height;

        ctx.beginPath();
        ctx.lineWidth = 2;
        ctx.strokeStyle = "black";

        let x = 0;
        for (let i = 0; i < amplitudes.length; i++) {
            const amplitude = amplitudes[i];
            for (let segment = 0; segment < waveSegmentWidth; segment++) {
                const y = height / 2 + amplitude * Math.sin((segment + x + currentOffset) / 20);
                ctx.lineTo(segment + x, y);
            }
            x += waveSegmentWidth; // Move to the next cycle's start
        }
        ctx.stroke();
    }

    function visualize() {
        analyser.getByteFrequencyData(dataArray); // Get frequency data from the audio stream

        // Calculate average audio level (simplified approach)
        const total = dataArray.reduce((acc, value) => acc + value, 0);
        const averageAudioLevel = total / dataArray.length;

        const scaledAmplitude = Math.min(averageAudioLevel / 5, 100); // Increase the scaling factor and cap it higher

        // Add the new amplitude for the next cycle
        amplitudes.push(scaledAmplitude);

        // Limit the number of stored amplitudes to fit the canvas
        const maxCycles = Math.ceil(canvas.width / waveSegmentWidth);
        if (amplitudes.length > maxCycles) {
            amplitudes.shift(); // Remove the oldest amplitude
        }

        // Clear the canvas and redraw the sine wave
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        drawSineWave();

        // Progress the wave
        currentOffset += 2; // Controls animation speed

        // Keep requesting animation frames to update sine wave continuously
        animationFrameId = requestAnimationFrame(visualize);
    }

    function stopVisualization() {
        cancelAnimationFrame(animationFrameId); // Stop the animation loop
    }

    const startRecognitionButton = document.getElementById('start-recognition');
    const stopRecognitionButton = document.getElementById('stop-recognition');
    const saveContentButton = document.getElementById('save-content');
    const startOverButton = document.getElementById('start-over'); // Added Start Over button
    const statusMessage = document.createElement('div');
    statusMessage.setAttribute('id', 'status-message');
    document.body.appendChild(statusMessage);

    function countdown(seconds, callback) {
        let remaining = seconds;
        statusMessage.innerHTML = `Starting in ${remaining}...`;
        const countdownInterval = setInterval(() => {
            remaining -= 1;
            statusMessage.innerHTML = `Starting in ${remaining}...`;
            if (remaining <= 0) {
                clearInterval(countdownInterval);
                callback();
            }
        }, 1000);
    }

    startRecognitionButton.addEventListener('click', function () {
        statusMessage.innerHTML = "Preparing to start... Please wait.";

        countdown(3, async () => {
            // Start the audio setup when the button is clicked
            await setupAudio();

            const speechConfig = SpeechSDK.SpeechConfig.fromSubscription(subscriptionKey, serviceRegion);
            speechConfig.speechRecognitionLanguage = 'ar-EG';

            const audioConfig = SpeechSDK.AudioConfig.fromDefaultMicrophoneInput();
            recognizer = new SpeechSDK.SpeechRecognizer(speechConfig, audioConfig);

            recognizer.startContinuousRecognitionAsync(() => {
                statusMessage.innerHTML = "You can start speaking now.";
            });

            recognizer.recognizing = (s, e) => {
                if (e.result.reason === SpeechSDK.ResultReason.RecognizingSpeech) {
                    // Use Azure's audio level data to control sine wave visualization
                    const audioLevel = e.result.audioLevel || 50;
                    visualizeSpeech(audioLevel); // Update sine wave visualization
                }
            };

            recognizer.recognized = (s, e) => {
                if (e.result.reason === SpeechSDK.ResultReason.RecognizedSpeech) {
                    appendRecognizedText(e.result.text);
                }
            };

            recognizer.canceled = (s, e) => {
                console.error(`Recognition canceled: ${e.errorDetails}`);
            };

            recognizer.sessionStopped = (s, e) => {
                console.log("Recording stopped");
                recognizer.stopContinuousRecognitionAsync();
            };

            stopRecognitionButton.disabled = false;
            startRecognitionButton.disabled = true;
        });
    });

    stopRecognitionButton.addEventListener('click', function () {
        if (recognizer) {
            recognizer.stopContinuousRecognitionAsync();
            stopVisualization(); // Stop sine wave animation
            stopRecognitionButton.disabled = true;
            startRecognitionButton.disabled = false;
            statusMessage.innerHTML = "";
        }
    });

    saveContentButton.addEventListener('click', function () {
        saveTranscribedContent(transcribedText);
    });

    // Handle the "Start Over" button
    startOverButton.addEventListener('click', function () {
        // Reset the sine wave visualization
        amplitudes.length = 0; // Clear the amplitudes array
        currentOffset = 0; // Reset the wave offset
        ctx.clearRect(0, 0, canvas.width, canvas.height); // Clear the canvas
        drawSineWave(); // Redraw the initial empty sine wave

        // Reset transcribed text
        transcribedText = '';
        document.getElementById('content').innerHTML = ''; // Clear displayed content
        stopVisualization(); // Optionally stop any ongoing visualization
        startRecognitionButton.disabled = false; // Enable start button again
        stopRecognitionButton.disabled = true; // Disable stop button
        statusMessage.innerHTML = "Started over, you can begin again.";
    });

    function appendRecognizedText(text) {
        const contentDiv = document.getElementById('content');
        let formattedText = text.trim();
        transcribedText += formattedText + ' ';

        formattedText = formattedText.replace(/\d+/g, '<span class="highlight-number">$&</span>');
        formattedText = formattedText.replace(/remember/gi, '<span class="highlight-remember">important</span>');

        formattedText = `<div class="paragraph">${formattedText}</div>`;
        contentDiv.innerHTML += ` ${formattedText}`;
        console.log('Text transcribed: ', formattedText); // Log the transcribed text
    }

    function saveTranscribedContent(content) {
        const user_id = document.getElementById('user-id').value;
        const folder_id = 1; // general folder
        const file_type = 1;
        console.log('Preparing to send transcribed content to server...');
        console.log('Content to save:', content);

        fetch('../includes/FileContent_class.php', { // Ensure this path is correct
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `content=${encodeURIComponent(content)}&user_id=${user_id}&folder_id=${folder_id}&file_type=${file_type}`
        })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url; // Handle redirection
                } else {
                    return response.text();
                }
            })
            .then(data => {
                console.log('Server response:', data);
                if (data.includes("Record updated successfully") || data.includes("Record created successfully")) {
                    console.log('Content saved successfully');
                } else {
                    console.log('Failed to save content. Response:', data);
                }
            })
            .catch(error => console.error('Error saving content:', error));

    }
});

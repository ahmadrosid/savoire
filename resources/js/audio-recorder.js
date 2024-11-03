class AudioRecorder extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({ mode: 'open' });
        this.mediaRecorder = null;
        this.audioChunks = [];
        this.isRecording = false;
        this.audioBlob = null;

        this.shadowRoot.innerHTML = `
            <style>
                :host {
                    display: block;
                }
                
                button {
                    padding: 8px 16px;
                    margin: 5px;
                    margin-left: 0px;
                    border-radius: 4px;
                    border: none;
                    cursor: pointer;
                    font-size: 14px;
                }
                
                button:disabled {
                    opacity: 0.5;
                    cursor: not-allowed;
                }
                
                .record-btn {
                    background-color: #ff4444;
                    color: white;
                }
                
                .record-btn.recording {
                    background-color: #cc0000;
                }
                
                .upload-btn {
                    background-color: #4CAF50;
                    color: white;
                }
                
                .download-btn {
                    background-color: #2196F3;
                    color: white;
                }
                
                .status {
                    margin-top: 10px;
                    font-size: 14px;
                    color: #666;
                }
                
                .controls {
                    display: flex;
                    gap: 10px;
                    flex-wrap: wrap;
                }
                
                audio {
                    margin-top: 10px;
                    width: 100%;
                }
            </style>
            
            <div>
                <div class="controls">
                    <button class="record-btn">Start Recording</button>
                    <button class="upload-btn" disabled>Upload</button>
                    <button class="download-btn" disabled>Download</button>
                </div>
                <div class="status">Ready to record</div>
                <audio controls style="display: none;"></audio>
            </div>
        `;

        this.recordBtn = this.shadowRoot.querySelector('.record-btn');
        this.uploadBtn = this.shadowRoot.querySelector('.upload-btn');
        this.downloadBtn = this.shadowRoot.querySelector('.download-btn');
        this.statusDiv = this.shadowRoot.querySelector('.status');
        this.audioElement = this.shadowRoot.querySelector('audio');

        this.setupEventListeners();
    }

    async setupRecording() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            this.mediaRecorder = new MediaRecorder(stream);

            this.mediaRecorder.ondataavailable = (event) => {
                this.audioChunks.push(event.data);
            };

            this.mediaRecorder.onstop = () => {
                this.audioBlob = new Blob(this.audioChunks, { type: 'audio/wav' });
                const audioUrl = URL.createObjectURL(this.audioBlob);
                this.audioElement.src = audioUrl;
                this.audioElement.style.display = 'block';
                this.uploadBtn.disabled = false;
                this.downloadBtn.disabled = false;
            };

        } catch (error) {
            throw new Error('Microphone access denied');
        }
    }

    setupEventListeners() {
        this.recordBtn.addEventListener('click', () => this.toggleRecording());
        this.uploadBtn.addEventListener('click', () => this.uploadAudio());
        this.downloadBtn.addEventListener('click', () => this.downloadAudio());
    }

    toggleRecording() {
        if (!this.isRecording) {
            this.setupRecording()
                .then(() => {
                    this.startRecording();
                })
                .catch(error => {
                    this.statusDiv.textContent = `Error: ${error.message}`;
                });
        } else {
            this.stopRecording();
        }
    }

    startRecording() {
        this.audioChunks = [];
        this.mediaRecorder.start();
        this.isRecording = true;
        this.recordBtn.textContent = 'Stop Recording';
        this.recordBtn.classList.add('recording');
        this.statusDiv.textContent = 'Recording...';
        this.audioElement.style.display = 'none';
        this.uploadBtn.disabled = true;
        this.downloadBtn.disabled = true;
    }

    stopRecording() {
        this.mediaRecorder.stop();
        this.isRecording = false;
        this.recordBtn.textContent = 'Start Recording';
        this.recordBtn.classList.remove('recording');
        this.statusDiv.textContent = 'Recording stopped';
    }

    async uploadAudio() {
        if (!this.audioBlob) return;

        const formData = new FormData();
        formData.append('audio', this.audioBlob, 'recording.wav');

        this.statusDiv.textContent = 'Uploading...';
        this.uploadBtn.disabled = true;

        try {
            const response = await fetch(this.getAttribute('api-endpoint') || '/upload', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();
            this.statusDiv.textContent = 'Upload successful!';
            
            this.dispatchEvent(new CustomEvent('upload-complete', {
                detail: result,
                bubbles: true,
                composed: true
            }));
        } catch (error) {
            this.statusDiv.textContent = `Upload failed: ${error.message}`;
            this.uploadBtn.disabled = false;
        }
    }

    downloadAudio() {
        if (!this.audioBlob) return;

        // Create a download link
        const downloadLink = document.createElement('a');
        downloadLink.href = URL.createObjectURL(this.audioBlob);
        
        // Get current timestamp for filename
        const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
        downloadLink.download = `recording-${timestamp}.wav`;
        
        // Append link to body, click it, and remove it
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
        
        this.statusDiv.textContent = 'Audio downloaded';
        
        // Dispatch download event
        this.dispatchEvent(new CustomEvent('download-complete', {
            bubbles: true,
            composed: true
        }));
    }

    // Optional: Add method to get recording duration
    getRecordingDuration() {
        return this.audioElement.duration;
    }

    // Optional: Add method to get audio blob size
    getRecordingSize() {
        return this.audioBlob ? this.audioBlob.size : 0;
    }
}

customElements.define('audio-recorder', AudioRecorder);

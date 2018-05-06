<template>
  <div>
    <div @click="$router.go(-1)" class="back">
        <i class="fas fa-chevron-left"></i>
        &nbsp;Back
    </div>

    <div class="full-height flex-center">
        <div class="flex-wrapper col-11">
            <p class="text-center semiBold green f-lg">What is that song?</p>
            <hr>

            <div v-show="recordingData.length == 0"  class="row justify-content-center mb-3 level">
                <div class="col-4">
                    <button class="btn btn-block btn-outline-danger" @click.stop.prevent="toggleRecording">
                        <div :class="startRecording">
                            <i class="fas fa-microphone f-lg m-3"></i>
                        </div>

                        <div :class="stopRecording">
                            <i class="fas fa-microphone-slash f-lg m-3" ></i>
                        </div>
                    </button>
                </div>

                <div class="col-12 red semiBold mt-3">
                    <p class="f-reg mb-0 text-center" v-show="!isRecording">Start recording</p>
                    <p class="f-reg mb-0 text-center" v-show="isRecording">Stop recording</p>
                </div>
            </div>


            <div v-if="dataUrl.length > 0" class="row mb-3 level">
                <div class="col-6 border-right">
                    <div class="row justify-content-center">
                        <div class="col-8">
                            <button @click.stop.prevent="togglePlay" class="btn btn-block btn-outline-purple">
                                <div :class="startPlayback">
                                    <i class="fas fa-play f-lg m-3"></i> 
                                </div>

                                <div :class="pausePlayback">
                                    <i class="fas fa-pause f-lg m-3"></i> 
                                </div>
                            </button>
                        </div>

                        <div class="col-12 purple semiBold mt-3">
                            <p class="f-reg mb-0 text-center" v-show="!isPlaying">Play recording</p>
                            <p class="f-reg mb-0 text-center" v-show="isPlaying">Pause recording</p>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="row justify-content-center">
                        <div class="col-8">
                            <button @click.stop.prevent="removeRecording" class="btn btn-block btn-outline-warning">
                                <i class="fas fa-redo-alt f-lg m-3"></i>
                            </button>
                        </div>

                        <div class="col-12 orange semiBold mt-3">
                            <p class="f-reg mb-0 text-center">Record again</p>
                        </div>
                    </div>
                </div>
            </div>

            <audio id="audio" @ended="playAgain" preload="auto" :src="dataUrl"></audio>

            <hr>

            <div class="row">
                <div class="col-12">
                    <form @submit.prevent="create">
                        <div class="form-group">
                            <label class="semiBold pl-3" for="description">Provide more info (optional)</label>
                            <textarea id="description" class="form-control" placeholder="Mid 80s Pop/Rock tune..." rows="3" v-model="description" maxlength="150"></textarea>
                        </div>

                        <button v-if="dataUrl.length > 0" class="btn btn-block btn-outline-success">
                            <i class="fas fa-check"></i> Submit recording
                        </button>
                    </form>
                </div>
            </div>


            
        </div>
    </div>
  </div>
</template>

<script>
export default {
    data() {
        return {
            isRecording: false,
            isPlaying: false,
            audioRecorder: null,
            audioElement: null,
            recordingData: [],
            description: '',
            dataUrl: '',
            postID: false,
            recorder: false
        }
    },

    mounted(){
        this.init()
        return this.audioElement = document.getElementById("audio")
    },

    computed: {
        startRecording() {
            return this.isRecording == false ?  'inline-block' : 'hidden'
        },

        stopRecording() {
            return this.isRecording == true ? 'inline-block' : 'hidden'
        },

        startPlayback() {
            return this.isPlaying == false ?  'inline-block' : 'hidden'
        },

        pausePlayback() {
            return this.isPlaying == true ? 'inline-block' : 'hidden'
        },
    },

    methods: {
        init() {
            navigator.mediaDevices.getUserMedia = navigator.mediaDevices.getUserMedia || navigator.mediaDevices.webkitGetUserMedia || navigator.mediaDevices.mozGetUserMedia
            if(navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({
                    audio: true,
                    video: false
                })
                .then()
                .catch(err => console.log(err))
            }
        },

        toggleRecording() {
            this.isRecording = !this.isRecording

            if (this.isRecording) {
                navigator.mediaDevices.getUserMedia({ audio: true }).then(stream => {
                    this.recorder = new MediaRecorder(stream)

                    // Set record to <audio> when recording will be finished
                    this.recorder.addEventListener('dataavailable', e => {
                        this.recordingData.push(e.data)
                        this.dataUrl = URL.createObjectURL(e.data)
                        const blob = new Blob(this.recordingData, { type: 'audio/webm'})  
                    })

                    // Start recording
                    this.recorder.start()
                    
                })
            } else {
                this.recorder.stop()
            }
        },

        removeRecording() {
            this.isRecording = false
            this.isPlaying = false
            this.recordingData = []
            this.dataUrl = ''
        },

        togglePlay() {
            if (this.audioElement.paused === false) {
                this.audioElement.pause()
            } else {
                this.audioElement.play()
            }

            this.isPlaying = !this.isPlaying
        },

        playAgain() {
            this.isPlaying = !this.isPlaying
        },

        create() {
            axios.post('/posts', {
                user_id: App.user.id,
                description: this.description,
            })
                .then(response => {                    
                    this.postID = response.data
                    this.submitRecording()
                })
                .catch(err => console.log(err))
            
        },

        submitRecording() {
            const le = this
            const blob = new Blob(le.recordingData , { type: 'audio/webm'})
            let formData = new FormData()

            formData.append('recording', blob)
            formData.append('postID', this.postID)

            axios.post('/recordings', formData )
                .then(response => {
                    flash('Your recording has been submitted!')
                    this.$router.push('home')
                })
                .catch(err => {
                    flash('Oops! Something went wrong', 'danger')
                    console.log(err)
                })
        },
    }

}
</script>

<style>

</style>

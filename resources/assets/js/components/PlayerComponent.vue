<template>
    <div class="row justify-content-center">
        <div class="col-10">
            <button @click.stop.prevent="togglePlay" class="btn btn-block btn-outline-purple p-1">
                <div :class="startPlayback">
                    <i class="fas fa-play f-md m-2"></i> 
                </div>

                <div :class="pausePlayback">
                    <i class="fas fa-pause f-md m-2"></i> 
                </div>
            </button>
        </div>

        <audio :id="playback" @ended="playAgain" preload="auto" :src="path"></audio>
    </div>
</template>

<script>
export default {
    props: ['path', 'postID'],

    data() {
        return {
            isPlaying: false,
            audioElement: null,
            playback: 'playback-' + this.postID,
        };
    },

    mounted(){
        return this.audioElement = document.getElementById(this.playback)
    },

    computed: {
        startPlayback() {
            return this.isPlaying == false ?  'inline-block' : 'hidden'
        },

        pausePlayback() {
            return this.isPlaying == true ? 'inline-block' : 'hidden'
        },
    },

    methods: {
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
    }

}
</script>

<style>

</style>

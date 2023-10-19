<template>
    <button type="button"
            @click="runProgress()"
            class="btn btn-warning"
            :disabled="currentValue == 'success' || fullImport == 1"
            :title="started">
      <span v-if="progress == true && currentValue !== 'success'" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      <i v-else v-bind:class="currentValue == 'success' ? 'fas fa-check' : 'fas fa-play'"></i>
    </button>

</template>

<script>
export default {

    name: "ProgressSpinnerComponent",

    props: {
      started: {
        type: String,
        required: true
      },
        getProgress: {
            type: String,
            required: true
        },
      fullImport: {
        type: Boolean,
        required: false
      }
    },

    created() {
        //this.getCurrentProgress();
        //setInterval(this.getCurrentProgress, 1000)
    },

    data(){
        return {
            currentValue: 0,
          progress: false
        }
    },

    methods: {
        runProgress(){
          this.getCurrentProgress();
          this.progress = true;
          setInterval(this.getCurrentProgress, 1000)
        },
        getCurrentProgress() {
            axios
                .get(this.getProgress)
                .then(response => {
                    let data = response.data;
                    this.currentValue = data.answer;
                    if (data.answer == "success")
                      this.progress =false;
                })
        }
    }
}
</script>

<style scoped>

</style>

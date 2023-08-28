<template>
    <button type="button"
            @click="runProgress()"
            class="btn btn-warning"
            :title="title">
      <span v-if="start == true && currentValue !== 'success'" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      <i v-else class="fas fa-play"></i>
    </button>

</template>

<script>
export default {

    name: "ProgressSpinnerComponent",

    props: {
      title: {
        type: String,
        required: true
      },
        getProgress: {
            type: String,
            required: true
        },
    },

    created() {
        //this.getCurrentProgress();
        //setInterval(this.getCurrentProgress, 1000)
    },

    data(){
        return {
            currentValue: 0,
          start: false
        }
    },

    methods: {
        runProgress(){
          this.getCurrentProgress();
          this.start = true;
          setInterval(this.getCurrentProgress, 1000)
        },
        getCurrentProgress() {
            axios
                .get(this.getProgress)
                .then(response => {
                    let data = response.data;
                    this.currentValue = data.answer;
                    if (data.answer == "success")
                      this.start =false;
                })
        }
    }
}
</script>

<style scoped>

</style>

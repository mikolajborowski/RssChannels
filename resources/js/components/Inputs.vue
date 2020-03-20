<style scoped>
    .container{
        padding: 10px;
    }
</style>
<template>
    <div class="container">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Rss channel and email</span>
            </div>
            <input @change='$emit("rssChannelChange", rss_channel_input)' v-model='rss_channel_input' type="text" aria-label="First name" class="form-control">
            <input @change='emailChanged()' v-model="email" type="email" aria-label="Last name" class="form-control" :readonly='read_only_email'>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                read_only_email: false,
                rss_channel_input: '',
                email: ''
            }
        },
        methods:{
            emailChanged: function() {
                if(/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/.test(this.email)){
                    this.read_only_email = true;
                    this.$emit("emailChange", this.email);
                }else{
                    this.$emit("setError", 'Not an email address, type again!');
                }
            }
        }
    }
</script>
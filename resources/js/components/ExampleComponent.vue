<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
<template>
    <div class="container">
        <div class="row justify-content-center">
            <div style='position:fixed; z-index: 10000'>
                <transition name="fade">
                    <div class="alert alert-success" v-if='isSuccess' role="alert">
                        Success!
                    </div>
                    <div class="alert alert-danger" v-if='isError' role="alert">
                        {{error}}
                    </div>
                </transition>
            </div>
           <Inputs
                :key="inputs_key"
                @rssChannelChange='rssChannelChange'
                @emailChange='emailChange'
                @setError='setError'
           ></Inputs>
           <div class="container">
                <div class="row">
                    <div class="col-sm">
                        <RssContent
                            :key="submit_key"
                            :content="content"
                        ></RssContent>
                    </div>
                    <div class="col-sm">
                        <RssList
                            :key="channels_key"
                            @reloadChannels='reloadChannels'
                        ></RssList>
                    </div>
                </div>
            </div>
            <Buttons
                @channelSubmit='channelSubmit'
                @formSubmit='formSubmit'
            ></Buttons>
        </div>
    </div>
</template>

<script>
    import Inputs from './Inputs';
    import RssContent from './RssContent';
    import RssList from './RssList';
    import Buttons from './Buttons';
    export default {
        components:{
            Inputs,
            RssContent,
            RssList,
            Buttons
        },
        data: function () {
            return {
                rss_channel_to_save: '',
                emial_to_save: '',
                isError: false,
                error: 'Something goes wrong!',
                isSuccess: false,
                channels_key: 0,
                submit_key: 0,
                inputs_key: 0,
                content: 'To load rss channels content click send!'
            }
        },
        methods:{
            rssChannelChange: function(channel) {
                this.rss_channel_to_save = channel;
            }, 
            emailChange: function(email){
                this.emial_to_save = email;
            },
            setSuccess: function(){
                this.isSuccess = true;
                const self = this;
                setTimeout(function(){ self.isSuccess = false; }, 3000);
            },
            setError: function(value){
                if(typeof value == 'string'){
                    this.error = value;
                }
                this.isError = true;
                const self = this;
                setTimeout(function(){ self.isError = false; this.error = 'Something goes wrong!'; }, 3000);
            },
            formSubmit: function(){
                const self = this;
                if(/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/.test(this.emial_to_save) == false){
                    self.setError('Not an email address, type again!');
                    return;
                }
                axios.post("/formSubmit")
                .then(function (response) {
                    self.content = response.data;
                    self.submit_key++;
                    self.setSuccess();
                })
                .catch(function (error) {
                    self.setError();
                });
            },
            channelSubmit: function(){
                try{
                    if(this.rss_channel_to_save == ''){
                        this.setError('Empty url!');
                        return;
                    }
                    const response = axios.post("/channelSubmit", {'channel': this.rss_channel_to_save});
                    this.reloadChannels();
                    this.setSuccess();
                }catch(e){
                    this.setError();
                }
            },
            reloadChannels: function(){
                this.channels_key++;
            }
        }
    }
</script>

<style scoped>
    .container{
        padding: 10px;
    }
</style>
<template>
    <div class="container">
        <ul class="list-group" v-if='data_ready'>
            <li class="list-group-item" v-for='item in channels'>
                {{item.channel}}
                <button @click="deleteChannel(item.id)" type="button" class="close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        mounted(){
            this.getChannels();
        },
        data: function () {
            return {
                channels: {},
                data_ready: false
            }
        },
        methods:{
            getChannels: async function() {
                const response = await axios.get("/getChannelsList");
                this.channels = response.data;
                this.data_ready = true;
            },
            deleteChannel: function(id){
                const delete_response = axios.post("/deleteChannel", {'id': id});
                this.$emit('reloadChannels');
            }
        }
    }
</script>
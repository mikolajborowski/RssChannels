<style scoped>
    .container{
        padding: 10px;
    }
</style>
<template>
    <div class="container">
        <div class="jumbotron jumbotron-fluid">
            <div style='max-height: 40vh!important; max-width:40vw!important; overflow:auto;' class="container">
                <div v-for='item in content' v-html='item.data'></div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['content'],
        mounted(){
            console.log('DATA ', this.content);
        }
    }
</script>
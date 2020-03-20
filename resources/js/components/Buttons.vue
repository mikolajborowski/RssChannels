<style scoped>
    .container{
        padding: 10px;
        margin-top: 20px;
    }
</style>
<template>
    <div class="container">
        <button @click='$emit("channelSubmit")' type="button" class="btn btn-primary btn-lg">Save</button>
        <button @click='$emit("formSubmit")' type="button" class="btn btn-success btn-lg">Send</button>
        <p style="color: gray">Created by Mikołaj Borowski</p>

    </div>
</template>
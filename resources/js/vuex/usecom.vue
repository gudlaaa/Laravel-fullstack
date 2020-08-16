<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <h1>I will show you how other component react to changes</h1>
                <h2>Master counter is: {{$store.state.counter}}</h2>
                <div><comA></comA></div>
                <div><comB></comB></div>
                <div><comC></comC></div>

            </div>
            <Button @click="increaseAll">Increase all</Button>
        </div>
    </div>
</template>

<script>
import comA from './comA'
import comB from './comB'
import comC from './comC'
export default {
    created(){

    },
    components: {
        comA,
        comB,
        comC
    },
    methods:{
        increaseAll(){
            this.$store.commit('startTheCounter', 1)
        }
    }  
}
</script>
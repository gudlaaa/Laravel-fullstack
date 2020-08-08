<template>
    <div>
        <h1>The number is: {{views}}</h1>
        <button @click='updateCount(1)'>Increase</button>
        <button @click='updateCount(-1)'>Decrease</button>

        <br><br><br>
        <!-- <h1>{{blog[0].title}}</h1> -->
        <div v-for='{blog, i} in blogs' :key='i'>
            <h1>{{blog.title}}</h1>
            <p>{{blog.post}}</p>
        </div>

    </div>
</template>

<script>
export default {
    data(){
        return {
            views: 0,
            blogs: [],
        }
    },
    methods: {
        updateCount(number){
            if (this.views >= 0){
                this.views += number
            } else {
                this.views = 0
            }
            
        }
    },
    created(){
        this.views = 10
        let posts = [{title: 'This is a blog title 1', 'post' : 'This is a blog post 1', id: 1},
        {title: 'This is a blog title 2', 'post' : 'This is a blog post 2', id: 2},
        {title: 'This is a blog title 3', 'post' : 'This is a blog post 3', id: 3},
        {title: 'This is a blog title 4', 'post' : 'This is a blog post 4', id: 4}];
        this.blogs = posts
    } 
}
</script>
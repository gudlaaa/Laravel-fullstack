<template>
    <div>
        <div class="content">
			<div class="container-flud">
				
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Blogs <Button @click="$router.push('/createBlog')" v-if="isWritePermitted"><Icon type="md-add" />Add</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Title</th>
								<th>Categories</th>
								<th>Tags</th>
								<th>View</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(blog, i) in this.blogs"  :key="i" v-if="blogs.length">
							<!-- <tr v-for="tag in tags" :key="tag.id" v-if="tags.length"> -->
								<td >{{blog.id}}</td>
								<td class="_table_name">{{blog.title}}</td>
								<td >
									<span v-for="(c, j) in blog.cat" :key="j" v-if="blog.cat.length"><Tag>{{c.categoryName}}</Tag></span>
								</td>
								<td >
									<span v-for="(t, k) in blog.tag" :key="k" v-if="blog.tag.length"><Tag>{{t.tagName}}</Tag></span>
								</td>
								<td>{{blog.views}}</td>
								<td>
									<Button type="info" size="small" @click="" v-if="isUpdatePermitted">Visit blog</Button>
									<Button type="info" size="small" @click="$router.push(`/editblog/${blog.id}`)" v-if="isUpdatePermitted">Edit</Button>
									<Button type="error" size="small" @click="showDeleteModal(blog, i)" :loading="blog.isDeleting" v-if="isDeletePermitted">Delete</Button>
									<!-- <button class="_btn btn-primary" type="button">Edit</button>
									<button class="_btn _action_btn make_btn1" type="button">Delete</button> -->
								</td>
							</tr>
								<!-- ITEMS -->


						</table>
					</div>
				</div>
				 <Page :total="100" />
				
				<deleteModal></deleteModal>

			</div>
		</div>
    </div>
</template>

<script>
import deleteModal from '../components/deleteModal'
import { mapGetters } from 'vuex';
export default {

	data(){
		return{
			blogs: [],
			isAdding: false,
			deleteModal: false,
			isDeleting: false,
			tags: [],
			editData: {
				tagName: '',
			},
			deleteData: {},
		}
	},
	methods: {
		showDeleteModal(blog, index){
			const deleteModalObj = {
				showDeleteModal: true,
				deleteUrl: 'app/delete_blog',
				data: {id: blog.id},
				deletingIndex: blog.id,
				isDeleted: false,
				msg: 'Are you sure you want to delete this blog?',
				successMsg: 'Blog has been deleted successfully.',
			}

			this.$store.commit('showDeleteModalObj', deleteModalObj)
			console.log('delete Method is called')
		},
		async deleteTag() {
			var tagObj = this.deleteData;
			this.$set(tagObj, 'isDeleting', true)
			const res = await this.callApi('post', 'app/delete_tag', tagObj)
			if (res.status == 200) {
				this.tags.splice(tagObj.index,1);
				this.deleteModal = false;
				this.s('Tag has been deleted successfully')
			} else {
				this.swr()
			}
		}
		
	},
	async created(){
		const res = await this.callApi('get', 'app/blogsdata', '');
		//console.log(res.data);
		if (res.status == 200){
			this.blogs = res.data;
		} else {
			this.swr()
		}
	},
	components: {
		deleteModal
	},
	watch: {
		getDeleteModalObj(obj){
			console.log(obj)
			if(obj.isDeleted){

				console.log(obj.deletingIndex)
				this.blogs.splice(obj.deletingIndex,1);
			}
		}
	}
}
</script>
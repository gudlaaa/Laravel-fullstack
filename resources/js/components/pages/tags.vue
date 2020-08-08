<template>
    <div>
        <div class="content">
			<div class="container-flud">
				
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Tags <Button @click="addModal = true"><Icon type="md-add" />Add</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Tag Name</th>
								<th>Created Date</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<!-- <tr v-for="{tag, i} in this.tags" :key="i" v-if="tags.length"> -->
							<tr v-for="tag in tags" :key="tag.id" v-if="tags.length">
								<td class="_table_name">{{tag.id}}</td>
								<td>{{tag.tagName}}</td>
								<td>{{tag.created_at}}</td>
								<td>
									<Button type="info" size="small">Edit</Button>
									<Button type="error" size="small">Delete</Button>
									<!-- <button class="_btn btn-primary" type="button">Edit</button>
									<button class="_btn _action_btn make_btn1" type="button">Delete</button> -->
								</td>
							</tr>
								<!-- ITEMS -->


						</table>
					</div>
				</div>
				 <Page :total="100" />
				<Modal
					v-model="addModal"
					title="Add Tag"
					:mask-closable="false"
					:closable="false">
					<Input v-model="data.tagName" placeholder="Add tag name" style="width: 300px" />
					<div slot="footer">
						<Button type="default" @click="addModal = false">Close</Button>
						<Button type="primary" @click="addTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding..' : 'Add tag'}}</Button>
					</div>
				</Modal>

			</div>
		</div>
    </div>
</template>

<script>
export default {

	data(){
		return{
			data: {
				tagName: '',
				
			},
			addModal: false,
			isAdding: false,
			tags: []

		}
	},
	methods: {
		async addTag () {
			if ( this.data.tagName.trim() == '') return this.e('Tag name is required');
			const res = await this.callApi('post', 'app/create_tag', {tagName: this.data.tagName});
			console.log(res);
			if (res.status == 201){
				this.tags.unshift(res.data);
				this.s('Tag added successfully');
				this.addModal = false;
			} else {
				this.swr()
			}
		}
		
	},
	async created(){
		const res = await this.callApi('get', 'app/get_tags', '');
		//console.log(res.data);
		if (res.status == 200){
			this.tags = res.data;
		} else {
			this.swr()
		}
	}
}
</script>
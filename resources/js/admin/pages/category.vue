<template>
    <div>
        <div class="content">
			<div class="container-flud">
				
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Categories <Button @click="addModal = true"><Icon type="md-add" />Add</Button></p>

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
							<tr v-for="(tag, i) in this.tags"  :key="i" v-if="tags.length">
							<!-- <tr v-for="tag in tags" :key="tag.id" v-if="tags.length"> -->
								<td class="_table_name">{{tag.id}}</td>
								<td>{{tag.tagName}}</td>
								<td>{{tag.created_at}}</td>
								<td>
									<Button type="info" size="small" @click="showEditModal(tag, i)">Edit</Button>
									<Button type="error" size="small" @click="showDeleteModal(tag, i)" :loading="tag.isDeleting" >Delete</Button>
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
					title="Add Category"
					:mask-closable="false"
					:closable="false">
                    <div class="space"></div>
                    <Input v-model="data.tagName" placeholder="Add category name" />
                    <div class="space"></div>
                    <Upload
                        type="drag"
						:headers="{'x-csrf-token' : this.csrfToken(), 'X-Requested-With' : 'XMLHttpRequest'}"
						:on-success="handleSuccess"
						:on-error="handleError"
						:max-size="2048"
						:on-format-error="handleFormatError"
						:on-exceeded-size="handleMaxSize"
                        action="/app/upload">
                        <div style="padding: 20px 0">
                            <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                            <p>Click or drag files here to upload</p>
                        </div>
                    </Upload>
					<div class="image_thumb" v-if="data.iconImage">
						<img :src="'/uploads/' + data.iconImage" />
					</div>
					
					<div slot="footer">
						<Button type="default" @click="addModal = false">Close</Button>
						<Button type="primary" @click="addTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding..' : 'Add tag'}}</Button>
					</div>
				</Modal>
				<Modal
					v-model="editModal"
					title="Edit Tag"
					:mask-closable="false"
					:closable="false">
					<Input v-model="editData.tagName" placeholder="Edit tag name" style="width: 300px" />
					<div slot="footer">
						<Button type="default" @click="editModal = false">Close</Button>
						<Button type="primary" @click="editTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing..' : 'Edit tag'}}</Button>
					</div>
				</Modal>
				<!-- delete modal -->
				<Modal v-model="deleteModal" width="360">
					<p slot="header" style="color:#f60;text-align:center">
						<Icon type="ios-information-circle"></Icon>
						<span>Delete confirmation</span>
					</p>
					<div style="text-align:center">
						<p>Are you sure you want to delete this tag?</p>
					</div>
					<div slot="footer">
						<Button type="error" size="large" long :loading="false" :disabled="false" @click="deleteTag">Delete</Button>
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
				iconImage: '',
				categoryName: '',
			},
			addModal: false,
			editModal: false,
			isAdding: false,
			deleteModal: false,
			tags: [],
			editData: {
				tagName: '',
			},
            deleteData: {},
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
				this.data.tagName = ''
			} else {
				if(res.status == 422){
					if(res.data.errors.tagName){
						this.e(res.data.errors.tagName[0])
					}
				} else {
					this.swr()
				}
				
			}
		},
		async editTag () {
			if ( this.editData.tagName.trim() == '') return this.e('Tag name is required');
			const res = await this.callApi('post', 'app/edit_tag', this.editData);
			console.log(res);
			if (res.status == 200){
				this.tags[this.editData.index].tagName = this.editData.tagName
				this.s('Tag has been edited successfully');
				this.editModal = false;
			} else {
				if(res.status == 422){
					if(res.data.errors.tagName){
						this.e(res.data.errors.tagName[0])
					}
				} else {
					this.swr()
				}
				
			}
		},
		showEditModal(tagObj, index){
			var obj = {
				id: tagObj.id,
				tagName: tagObj.tagName,
				index: index
			}
			this.editData = obj
			this.editModal = true
		},
		showDeleteModal(tagObj, index){
			var obj = {
				id: tagObj.id,
				tagName: tagObj.tagName,
				index: index
			}
			this.deleteData = obj
			this.deleteModal = true
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
		},
		handleSuccess (res, file) {
			this.data.iconImage = res
		},
		handleError (res, file) {
			console.log('res' + res);
			console.log(file.errors.file[0]);
			this.$Notice.warning({
				title: 'The file format is incorrect',
				desc: file.errors.file.length ? file.errors.file[0] : 'Something went wrong'
			});
		},
		handleFormatError (file) {
			this.$Notice.warning({
				title: 'The file format is incorrect',
				desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
			});
		},
		handleMaxSize (file) {
			this.$Notice.warning({
				title: 'Exceeding file size limit',
				desc: 'File  ' + file.name + ' is too large, no more than 2M.'
			});
		},
		
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
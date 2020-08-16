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
								<th>Category Name</th>
								<th>Category Image</th>
								<th>Created Date</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(category, i) in this.categories"  :key="i" v-if="categories.length">
							<!-- <tr v-for="category in categories" :key="category.id" v-if="categories.length"> -->
								<td class="_table_name">{{category.id}}</td>
								<td>{{category.categoryName}}</td>
								<td class="_table_name"><img :src="'/uploads/' + category.iconImage"></td>
								<td>{{category.created_at}}</td>
								<td>
									<Button type="info" size="small" @click="showEditModal(category, i)">Edit</Button>
									<Button type="error" size="small" @click="showDeleteModal(category, i)" :loading="category.isDeleting" >Delete</Button>
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
                    <Input v-model="data.categoryName" placeholder="Add category name" />
                    <div class="space"></div>
                    <Upload
						ref="uploads"
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
					<!-- <div class="demo-upload-list" v-for="item in uploadList"> -->
					<div class="demo-upload-list" v-if="data.iconImage">
						<img :src="'/uploads/' + data.iconImage" />
						<div class="demo-upload-list-cover">
							<Icon type="ios-trash-outline" @click="deleteImage"></Icon>
						</div>
					</div>
					
					<div slot="footer">
						<Button type="default" @click="addModal = false">Close</Button>
						<Button type="primary" @click="addCategory" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding..' : 'Add Category'}}</Button>
					</div>
				</Modal>
				<Modal
					v-model="editModal"
					title="Edit Category"
					:mask-closable="false"
					:closable="false">
					<Input v-model="editData.categoryName" placeholder="Edit category name" />
					<Upload v-show="isIconImageNew"
						ref="editUploads"
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
					<!-- <div class="demo-upload-list" v-for="item in uploadList"> -->
					<div class="demo-upload-list" v-if="editData.iconImage">
						<img :src="'/uploads/' + editData.iconImage" />
						<div class="demo-upload-list-cover">
							<Icon type="ios-trash-outline" @click="deleteImage()"></Icon>
						</div>
					</div>
					
					<div slot="footer">
						<Button type="default" @click="editModal = false">Close</Button>
						<Button type="primary" @click="editCategory" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing..' : 'Edit Category'}}</Button>
					</div>
				</Modal>
				<!-- delete modal -->
				<Modal v-model="deleteModal" width="360">
					<p slot="header" style="color:#f60;text-align:center">
						<Icon type="ios-information-circle"></Icon>
						<span>Delete confirmation</span>
					</p>
					<div style="text-align:center">
						<p>Are you sure you want to delete this category?</p>
					</div>
					<div slot="footer">
						<Button type="error" size="large" long :loading="false" :disabled="false" @click="deleteCategory">Delete</Button>
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
			categories: [],
			editData: {
				categoryName: '',
				iconImage: '',
			},
			deleteData: {},
			isIconImageNew: false,
		}
	},
	methods: {
		async addCategory () {
			if ( this.data.categoryName.trim() == '') return this.e('Category name is required');
			if ( this.data.iconImage.trim() == '') return this.e('Category image is required');
			const res = await this.callApi('post', 'app/create_category', {categoryName: this.data.categoryName, iconImage: this.data.iconImage});
			console.log(res);
			if (res.status == 201){
				this.categories.unshift(res.data);
				this.s('Category added successfully');
				this.addModal = false;
				this.data.categoryName = ''
			} else {
				if(res.status == 422){
					if(res.data.errors.categoryName){
						this.e(res.data.errors.categoryName[0])
					}
				} else {
					this.swr()
				}
				
			}
		},
		async editCategory () {
			if ( this.editData.categoryName.trim() == '') return this.e('Category name is required');
			if ( this.editData.iconImage.trim() == '') return this.e('Category Image is required');
			const res = await this.callApi('post', 'app/edit_category', this.editData);
			console.log(res);
			if (res.status == 200){
				this.categories[this.editData.index].categoryName = this.editData.categoryName
				this.categories[this.editData.index].iconImage = this.editData.iconImage
				this.s('Category has been edited successfully');
				this.editModal = false;
			} else {
				if(res.status == 422){
					if(res.data.errors.categoryName){
						this.e(res.data.errors.categoryName[0])
					}
					if(res.data.errors.iconImage){
						this.e(res.data.errors.iconImage[0])
					}
				} else {
					this.swr()
				}
				
			}
		},
		showEditModal(categoryObj, index){
			var obj = {
				id: categoryObj.id,
				categoryName: categoryObj.categoryName,
				iconImage: categoryObj.iconImage,
				index: index
			}
			this.editData = obj
			this.editModal = true
		},
		showDeleteModal(categoryObj, index){
			var obj = {
				id: categoryObj.id,
				categoryName: categoryObj.categoryName,
				iconImage: categoryObj.iconImage,
				index: index
			}
			this.deleteData = obj
			this.deleteModal = true
		},
		async deleteCategory() {
			var categoryObj = this.deleteData;
			this.$set(categoryObj, 'isDeleting', true)
			const res = await this.callApi('post', 'app/delete_category', categoryObj)
			if (res.status == 200) {
				this.categories.splice(categoryObj.index,1);
				this.data.iconImage = categoryObj.iconImage
				this.deleteImage();
				this.deleteModal = false;
				this.s('Category has been deleted successfully')
			} else {
				this.swr()
			}
		},
		async deleteImage(){
			console.log('fdsf'+this.editModal + this.editData.iconImage);
			var image;
			if(this.editModal){
				this.isIconImageNew = true
				image = this.editData.iconImage
				this.editData.iconImage = ''
				this.$refs.editUploads.clearFiles()
			} else {
				image = this.data.iconImage
				this.data.iconImage = ''
				this.$refs.uploads.clearFiles()
			}
			console.log(image);
			const res = await this.callApi('post', 'app/delete_image', {imageName: image})
			if (res.status != 200){
				this.editData.iconImage = image
				this.data.iconImage = image
				
				this.swr();
			} else {
				this.editData.iconImage = ''
			}
		},
		handleSuccess (res, file) {
			if (!this.editModal){
				this.data.iconImage = res	
			} else {
				this.editData.iconImage = res
			}
			
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
		const res = await this.callApi('get', 'app/get_categories', '');
		//console.log(res.data);
		if (res.status == 200){
			this.categories = res.data;
		} else {
			this.swr()
		}
	}
}
</script>
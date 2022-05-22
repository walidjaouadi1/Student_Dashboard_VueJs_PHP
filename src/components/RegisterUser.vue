<template>
	<div class="registered">
		<h1 class="text-center">PHP, VUE.JS 3 , MySQL</h1>
	</div>
	<div class="container" v-if="!isDB_Connected">
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			{{ isDB_ConnectedMsg }}
			<button
				type="button"
				class="btn-close"
				data-bs-dismiss="alert"
				aria-label="Close"
			></button>
		</div>
	</div>
	<div class="container" v-else>
		<!-- show add students -->
		<div class="col-lg-12">
			<button class="float-end btn btn-info" @click="addModalDialog(true)">
				<FontIcon :icon="['fas', 'user']" /> Add New Student
			</button>
			<button
				class="float-start btn btn-info"
				v-if="studentData.length > 0"
				@click="deleteALLModalDialog(true)"
			>
				<FontIcon :icon="['fas', 'user-times']" />
				Delete New Student
			</button>
			<div class="clearfix"></div>
		</div>
		<hr />
		<!-- show error & success message  -->
		<div
			class="alert alert-danger alert-dismissible fade show"
			role="alert"
			v-if="errMsg"
		>
			{{ errMsg }}
			<button
				type="button"
				class="btn-close"
				data-bs-dismiss="alert"
				aria-label="Close"
			></button>
		</div>
		<div
			class="alert alert-success alert-dismissible fade show"
			role="alert"
			v-if="successMsg"
		>
			{{ successMsg }}
			<button
				type="button"
				class="btn-close"
				data-bs-dismiss="alert"
				aria-label="Close"
			></button>
		</div>
		<!-- student Table -->
		<div class="table-responsive">
			<table class="table table-border table-striped caption-top">
				<caption>
					List Of registered Students
				</caption>
				<thead>
					<tr class="bg-success text-light text-center">
						<th><FontIcon :icon="['fas', 'id-badge']" /> ID</th>
						<th><FontIcon :icon="['fas', 'user']" /> Name</th>
						<th><FontIcon :icon="['fas', 'envelope']" /> Email</th>
						<th><FontIcon :icon="['fas', 'phone']" /> Phone</th>
						<th><FontIcon :icon="['fas', 'user-cog']" /> Actions</th>
					</tr>
				</thead>
				<tbody>
					<tr class="text-center" v-for="(student, i) in studentData" :key="i">
						<td>{{ student.id }}</td>
						<td>{{ student.name }}</td>
						<td>{{ student.email }}</td>
						<td>{{ student.phone }}</td>
						<td>
							<button
								class="btn btn-danger"
								@click="
									deleteModalDialog(true);
									selectStudent(student);
								"
							>
								<FontIcon :icon="['fas', 'user-times']" />
								Delete
							</button>
							&nbsp;
							<button
								class="btn btn-warning"
								@click="
									updateModalDialog(true);
									selectStudent(student);
								"
							>
								<FontIcon :icon="['fas', 'user-edit']" /> Update
							</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<!-- show Add New Student Popup -->
		<div id="overlay" v-if="showAddModal">
			<div class="modal">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Add New Student</h5>
							<button
								type="button"
								class="btn-close"
								data-bs-dismiss="modal"
								aria-label="Close"
								@click="addModalDialog(false)"
							></button>
						</div>
						<div class="modal-body">
							<form action="#" method="post" @click.prevent>
								<div
									class="form-floating mb-3"
									:class="{ 'form-group--error': v$.newStudent.name.$error }"
								>
									<input
										type="text"
										class="form-control"
										id="floatingInput1"
										placeholder="abcd"
										v-model.trim="newStudent.name"
									/>
									<label for="floatingInput1"
										><FontIcon :icon="['far', 'user']" /> student name</label
									>
									<span class="error-feedback" v-if="v$.newStudent.name.$error">
										{{ v$.newStudent.name.$errors[0].$message }}
									</span>
								</div>
								<div
									class="form-floating mb-3"
									:class="{ 'form-group--error': v$.newStudent.email.$error }"
								>
									<input
										type="email"
										class="form-control"
										id="floatingInput2"
										placeholder="abcd"
										v-model.trim="newStudent.email"
									/>
									<label for="floatingInput2"
										><FontIcon :icon="['far', 'envelope']" /> Student Email
									</label>
									<span
										class="error-feedback"
										v-if="v$.newStudent.email.$error"
									>
										{{ v$.newStudent.email.$errors[0].$message }}
									</span>
								</div>
								<div
									class="form-floating mb-3"
									:class="{ 'form-group--error': v$.newStudent.phone.$error }"
								>
									<input
										type="email"
										class="form-control"
										id="floatingInput3"
										placeholder="abcd"
										v-model.trim="newStudent.phone"
									/>
									<label for="floatingInput3"
										><FontIcon :icon="['fas', 'phone']" /> Student Phone</label
									>
									<span
										class="error-feedback"
										v-if="v$.newStudent.phone.$error"
									>
										{{ v$.newStudent.phone.$errors[0].$message }}
									</span>
								</div>
								<div class="d-grid gap-2">
									<button
										type="button"
										class="btn btn-primary"
										@click="AddNewStudent()"
									>
										Add
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Show Edit Modal -->
		<div id="overlay" v-if="showUpdateModal">
			<div class="modal">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-warning">Update Student</h5>
							<button
								type="button"
								class="btn-close"
								data-bs-dismiss="modal"
								aria-label="Close"
								@click="updateModalDialog(false)"
							></button>
						</div>
						<div class="modal-body">
							<form action="#" method="post" @click.prevent>
								<div
									class="form-floating mb-3"
									:class="{
										'form-group--error': v$.currentStudent.name.$error,
									}"
								>
									<input
										type="text"
										class="form-control"
										id="floatingInput1"
										placeholder="abcd"
										v-model.trim="currentStudent.name"
									/>
									<label for="floatingInput1"
										><FontIcon :icon="['far', 'user']" /> student name</label
									>
									<span
										class="error-feedback"
										v-if="v$.currentStudent.name.$error"
									>
										{{ v$.currentStudent.name.$errors[0].$message }}
									</span>
								</div>
								<div
									class="form-floating mb-3"
									:class="{
										'form-group--error': v$.currentStudent.email.$error,
									}"
								>
									<input
										type="email"
										class="form-control"
										id="floatingInput2"
										placeholder="abcd"
										v-model.trim="currentStudent.email"
									/>
									<label for="floatingInput2"
										><FontIcon :icon="['far', 'envelope']" /> Student Email
									</label>
									<span
										class="error-feedback"
										v-if="v$.currentStudent.email.$error"
									>
										{{ v$.currentStudent.email.$errors[0].$message }}
									</span>
								</div>
								<div
									class="form-floating mb-3"
									:class="{
										'form-group--error': v$.currentStudent.phone.$error,
									}"
								>
									<input
										type="email"
										class="form-control"
										id="floatingInput3"
										placeholder="abcd"
										v-model.trim="currentStudent.phone"
									/>
									<label for="floatingInput3"
										><FontIcon :icon="['fas', 'phone']" /> Student Phone</label
									>
									<span
										class="error-feedback"
										v-if="v$.currentStudent.phone.$error"
									>
										{{ v$.currentStudent.phone.$errors[0].$message }}
									</span>
								</div>
								<div class="d-grid gap-2">
									<button
										type="button"
										class="btn btn-warning"
										@click="updateStudent()"
									>
										Update
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Delete Current User -->
		<div id="overlay" v-if="showDeleteModal">
			<div class="modal">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-danger">Delete Current Student</h5>
							<button
								type="button"
								class="btn-close"
								data-bs-dismiss="modal"
								aria-label="Close"
								@click="deleteModalDialog(false)"
							></button>
						</div>
						<div class="modal-body">
							<form action="" method="post" @click.prevent>
								<h6 class="texet-center">
									Are You Sure To Delete This Student ?
								</h6>
								<p>
									<span
										><FontIcon :icon="['fas', 'user']" />
										{{ currentStudent.name }}</span
									><br />
									<span
										><FontIcon :icon="['fas', 'envelope']" />
										{{ currentStudent.email }}</span
									><br />
									<span
										><FontIcon :icon="['fas', 'phone']" />
										{{ currentStudent.phone }}</span
									><br />
								</p>
								<div class="d-grid gap-2">
									<button class="btn btn-danger" @click="deleteStudent()">
										Delete Current Student
									</button>
									<button
										class="btn btn-primary"
										@click="deleteModalDialog(false)"
									>
										Cancel
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Delete All Students -->
		<div id="overlay" v-if="showDeleteAllModal">
			<div class="modal">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-danger">Delete Current Student</h5>
							<button
								type="button"
								class="btn-close"
								data-bs-dismiss="modal"
								aria-label="Close"
								@click="deleteALLModalDialog(false)"
							></button>
						</div>
						<div class="modal-body">
							<form action="#" method="post" @click.prevent>
								<h6 class="texet-center">
									Are You Sure To Delete All Student ?
								</h6>
								<div class="d-grid gap-2">
									<button
										type="button"
										class="btn btn-warning"
										@click="deleteAllStudent()"
									>
										Delete All Students
									</button>
									<button
										type="button"
										class="btn btn-primary"
										@click="deleteALLModalDialog(false)"
									>
										Cancel
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import axios from "axios";
import useValidate from "@vuelidate/core";
import { required, email, minLength, numeric } from "@vuelidate/validators";

export default {
	name: "RegisterUser",
	data() {
		return {
			v$: useValidate(),
			errMsg: "",
			successMsg: "",
			isDB_Connected: "",
			isDB_ConnectedMsg: "",
			studentData: [],
			showAddModal: false,
			showUpdateModal: false,
			showDeleteModal: false,
			showDeleteAllModal: false,
			newStudent: {
				name: "",
				email: "",
				phone: "",
			},
			currentStudent: {},
		};
	},
	validations() {
		return {
			newStudent: {
				name: { required, minLength: minLength(4) },
				email: { required, email },
				phone: { required, minLength: minLength(4), numeric },
			},
			currentStudent: {
				name: { required, minLength: minLength(4) },
				email: { required, email },
				phone: { required, minLength: minLength(4), numeric },
			},
		};
	},
	mounted() {
		this.getStudentsData();
		this.dbConnection();
	},
	methods: {
		addModalDialog(value) {
			this.showAddModal = value;
		},
		updateModalDialog(value) {
			this.showUpdateModal = value;
		},
		deleteModalDialog(value) {
			this.showDeleteModal = value;
		},
		deleteALLModalDialog(value) {
			this.showDeleteAllModal = value;
		},
		async getStudentsData() {
			let res = await axios.get(
				`http://localhost/php_projects/students_dashboard_vuejs/src/api/students.php?action=read`
			);
			const resData = res.data;
			if (res.status == 200) {
				if (resData.error) {
					this.errMsg = resData.message;
				} else {
					this.studentData = resData.students;
				}
			}
		},
		async deleteAllStudent() {
			this.deleteALLModalDialog(false);
			this.clearAllMsgs;
			let res = await axios.get(
				`http://localhost/php_projects/students_dashboard_vuejs/src/api/students.php?action=deleteAll`
			);
			const resData = res.data;
			if (res.status == 200) {
				if (resData.error) {
					// if error
					this.errMsg = resData.message;
				} else {
					this.successMsg = resData.message;
					this.studentData = "";
				}
			}
		},
		async deleteStudent() {
			var toForm = this.toFormData(this.currentStudent);
			this.deleteModalDialog(false);
			this.clearAllMsgs;
			let res = await axios.post(
				"http://localhost/php_projects/students_dashboard_vuejs/src/api/students.php?action=delete",
				toForm
			);
			// -----------------------------
			const resData = res.data;
			if (res.status == 200) {
				if (resData.error) {
					// if error
					this.errMsg = resData.message;
				} else {
					console.log(res.data);
					this.successMsg = resData.message;
					this.currentStudent = {};
					this.getStudentsData();
				}
			}
			// -----------------------------
		},

		clearAllMsgs() {
			this.errMsg = "";
			this.successMsg = "";
		},
		async dbConnection() {
			let res = await axios.get(
				`http://localhost/php_projects/students_dashboard_vuejs/src/api/students.php`
			);
			const resData = res.data;
			this.isDB_Connected = resData.is_db_connected;
			this.isDB_ConnectedMsg = resData.connection_msg;
		},
		toFormData(obj) {
			var fd = new FormData();
			for (var i in obj) {
				fd.append(i, obj[i]);
			}
			return fd;
		},
		async AddNewStudent() {
			this.v$.newStudent.$validate();
			console.log(this.v$);
			if (!this.v$.newStudent.$error) {
				this.addModalDialog(false);
				this.clearAllMsgs();
				var toForm = this.toFormData(this.newStudent);
				let res = await axios.post(
					"http://localhost/php_projects/students_dashboard_vuejs/src/api/students.php?action=add",
					toForm
				);
				// -----------------------------
				const resData = res.data;
				if (res.status == 200) {
					if (resData.error) {
						// if error
						this.errMsg = resData.message;
					} else {
						console.log(res.data);
						this.successMsg = resData.message;
						this.newStudent = { name: "", email: "", phone: "" };
						this.getStudentsData();
						this.v$.newStudent.$reset();
					}
				}

				//
				console.log("Success Validation");
			} else {
				console.log("fail Validation");
			}
		},
		async updateStudent() {
			this.v$.currentStudent.$validate();
			console.log(this.v$);
			if (!this.v$.currentStudent.$error) {
				this.updateModalDialog(false);
				this.clearAllMsgs();
				var toForm = this.toFormData(this.currentStudent);
				let res = await axios.post(
					"http://localhost/php_projects/students_dashboard_vuejs/src/api/students.php?action=update",
					toForm
				);
				// -----------------------------
				const resData = res.data;
				if (res.status == 200) {
					if (resData.error) {
						// if error
						this.errMsg = resData.message;
					} else {
						console.log(res.data);
						this.successMsg = resData.message;
						this.currentStudent = {};
						this.getStudentsData();
						this.v$.currentStudent.$reset();
					}
				}
				console.log("Success Validation");
			} else {
				console.log("fail Validation");
			}
		},
		selectStudent(std) {
			this.currentStudent = std;
		},
	},
};
</script>

<style scoped>
h1 {
	background-color: cadetblue;
	color: #fff;
	font-weight: bold;
	margin-top: 0;
	margin-bottom: 50px;
	padding: 15px;
}
#overlay {
	position: fixed;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background-color: rgb(0 0 0 / 47%);
}
.modal {
	display: block;
}
.form-group--error {
	color: red;
}
/* .form-group--error input {
	border-color: red;
} */
</style>

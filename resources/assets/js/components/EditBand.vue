<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    	<h2>{{pageName}}</h2>
                    </div>

                    <div class="panel-body band" v-if="band">
                    	<div>
							<label>Band Name (required)</label>
							<input type="text" v-model="band.name">
						</div>
						<div>
							<label>Start Date</label>
							<input type="date" v-model="band.start_date">
						</div>
						
						<div>
							<label>Still Active</label>
							<input type="radio" id="true" value="1" class="inline" v-model="stillActive">
							<label for="true" class="inline">Yes</label>
							<br>
							<input type="radio" id="false" value="0" class="inline" v-model="stillActive">
							<label for="false" class="inline">No</label>
						</div>
                                        
					    <div>
							<label>Website</label>
							<input type="url" v-model="band.website">
						</div>
						
						<div>
							<button type="submit" @click.stop="saveBand">Save</button>
						</div>
						<br>
						<div v-if="successMessage" class="success-message">{{successMessage}}</div>
                    	<div v-if="errorMessage" class="error-message">{{errorMessage}}</div>
                    </div>
                    
                    <div class="panel-body band" v-if="bandId">
                    	<a v-bind:href="'/bands/' + bandId">Band Page</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
    	data() {
    		return {
    			pageName: '',
				band: {
					id: null,
					name: '',
					start_date: null,
					website: null,
					still_active: true
				},
				bandId: this.id,
				errorMessage: null,
				successMessage: null
			}
    	},
    	props: [
    		'id'
    	],
        mounted() {
            console.log('EditBand Component mounted.');
        },
        created() {
			if (this.bandId) {
				this.fetchBand()
			}
			
			if (this.bandId) {
        		this.pageName = 'Edit Band';
				this.fetchBand()
			} else {
				this.pageName = 'New Band';
			} 
		},
		computed: {
		  stillActive: {
		  		// getter
			  	get() {
					if (!this.band) {
						return 0;
					}
		  
					return (this.band.still_active == true) ? 1 : 0;
				},
				// setter
			  	set(newValue) {
					if (!this.band) {
						return;
					}
		  			
		  			// API will convert integer to boolean for us
					return this.band.still_active = newValue;
				},
		  }
		},
        methods: {
        	clearMessages() {
        		var self = this
        		setTimeout(function() {
        			self.successMessage = null;
        			self.errorMessage = null;
        		}, 4500)
        	},
        	saveBand() {
				if (this.bandId) {
					this.updateBand();
					return;
				}
				
				var self = this
				var url = 'bands';
	
				self.successMessage = null;
				self.errorMessage = null;
					
				axios.post(url, self.band).then(function (response) {
					console.log('Band Saved');
					self.band = response.data.data;
					self.successMessage = 'Band Saved';
					window.location = "/bands/" + self.band.id;
					self.clearMessages();
				}).catch(function (error) {
					alert('Error: ' + error.response.data.details);
					console.log(error.response.data);
					self.clearMessages();
				});
			},
			updateBand() {
				var self = this
				var url = 'bands/' + this.bandId;
	
				self.successMessage = null;
				self.errorMessage = null;
	
				axios.put(url, self.band).then(function (response) {
					console.log('Band Saved');
					self.band = response.data.data;
					self.successMessage = 'Band Saved';
					self.clearMessages();
				}).catch(function (error) {
					alert('Error: ' + error.response.data.details);
					console.log(error.response.data);
					self.clearMessages();
				});
			},
			fetchBand() {
				var self = this
				axios.get('bands/' + this.bandId).then(function (response) {
					console.log('Band loaded');
					self.band = response.data.data;
				})
			}
		}
    }
</script>

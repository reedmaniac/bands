<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    	<h2>{{pageName}}</h2>
                    </div>

                    <div class="panel-body album" v-if="album">
						<div>
							<label>Album Name (required)</label>
							<input type="text" v-model="album.name">
						</div>
						<div>
							<label>Band (required)</label>
							<select name="band" v-model="album.band_id">
								<option value="">Choose Band</option>
								<template v-for="band in bands">
									<option :value="band.id">
										{{ band.name }}
									</option>
								</template>
							</select>
						</div>
						<div>
							<label>Release Date</label>
							<input type="date" v-model="album.release_date">
						</div>
						<div>
							<label>Producer</label>
							<input type="text" v-model="album.producer">
						</div>
						<div>
							<label>Number of Tracks</label>
							<input type="number" v-model="album.number_of_tracks">
						</div>
						<div>
							<label>Genre</label>
							<select v-model="album.genre">
								<option value="">Choose Genre</option>
								<template v-for="genre in genres">
									<option :value="genre">
										{{ genre }}
									</option>
								</template>
							</select>
						</div>
						
						<div>
							<button type="submit" @click.stop="saveAlbum">Save</button>
						</div>
						<div v-if="successMessage" class="success-message">{{successMessage}}</div>
                    	<div v-if="errorMessage" class="error-message">{{errorMessage}}</div>
                    </div>
                    
                    <div class="panel-body album" v-if="albumId">
                    	<a v-bind:href="'/albums/' + albumId">Album Page</a>
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
    			albumId: this.id,
				album: {
					name: '',
					band_id: null,
					release_date: '',
					producer: '',
					number_of_tracks: 1,
					genre: ''
				},
				bands: null,
				errorMessage: null,
				successMessage: null,
				genres: [
					'Blues',
					'Comedy',
					'Children’s Music',
					'Classical',
					'Country',
					'Electronic',
					'Holiday',
					'Opera',
					'Jazz',
					'Latino',
					'New Age',
					'Pop',
					'R&B/Soul',
					'Soundtrack',
					'Dance',
					'Hip-Hop',
					'Rap',
					'World',
					'Alternative',
					'Rock',
					'Christian & Gospel',
					'Reggae'
				]
			}
    	},
    	props: [
    		'id'
    	],
        mounted() {
            console.log('EditAlbum Component mounted.');
        },
        created() {
        	this.fetchBands()
        	
        	if (this.albumId) {
        		this.pageName = 'Edit Album';
				this.fetchAlbum()
			} else {
				this.pageName = 'New Album';
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
        	fetchBands() {
				var self = this
				axios.get('bands').then(function (response) {
					console.log('Bands loaded');
					self.bands = response.data.data;
				})
			},
			saveAlbum() {
				if (this.albumId) {
					this.updateAlbum();
					return;
				}
				
				var self = this
				var url = 'albums';
	
				self.successMessage = null;
				self.errorMessage = null;
					
				axios.post(url, self.album).then(function (response) {
					console.log('Album Saved');
					self.album = response.data.data;
					self.successMessage = 'Album Saved';
					window.location = "/albums/" + self.album.id;
					self.clearMessages();
				}).catch(function (error) {
					alert('Error: ' + error.response.data.details);
					console.log(error.response.data);
					self.clearMessages();
				});
			},
			updateAlbum() {
				var self = this
				var url = 'albums/' + this.albumId;
	
				self.successMessage = null;
				self.errorMessage = null;
	
				axios.put(url, self.album).then(function (response) {
					console.log('Album Saved');
					self.album = response.data.data;
					self.successMessage = 'Band Saved';
					self.clearMessages();
				}).catch(function (error) {
					alert('Error: ' + error.response.data.details);
					console.log(error.response.data);
					self.clearMessages();
				});
			},
			fetchAlbum() {
				var self = this
				var url = 'albums/' + this.albumId;
	
				axios.get(url).then(function (response) {
					console.log('Album loaded');
					self.album = response.data.data;
				})
			}
		}
    }
</script>

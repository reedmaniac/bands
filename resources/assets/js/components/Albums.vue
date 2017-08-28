<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    	<h2>
                    		Albums
                    		<span style="float:right; font-size: 0.7em;">
                    			<a href="/albums/new">+ Add Album</a>
                    		</span>
                    	</h2>
                    	
                    	<select name="band" v-model="currentBand">
                    		<option value="">Choose Band</option>
							<template v-for="band in bands">
								<option :value="band.id">
									{{ band.name }}
								</option>
							</template>
						</select>
                    </div>

					<table class="albums">
						<thead>
							<th><a href="#" @click="order('name')">Album</a></th>
							<th><a href="#" @click="order('band')">Band</a></th>
							<th><a href="#" @click="order('release_date')">Release Date</a></th>
							<th><a href="#" @click="order('producer')">Producer</a></th>
<!-- 							<th><a href="#" @click="order('number_of_tracks')"># of Tracks</a></th> -->
							<th><a href="#" @click="order('genre')">Genre</a></th>
							<th>Edit</th>
							<th>Delete</th>
						</thead>
						
						<tbody>
							<tr class="album" v-for="album in albums">
							  <td>
								<a v-bind:href="'/albums/' + album.id">
									{{ album.name }}
								</a>
							  </td>
							  <td>{{ album.band.name }}</td>
							  <td>{{ album.release_date }}</td>
							  <td>{{ album.producer }}</td>
<!-- 							  <td>{{ album.number_of_tracks }}</td> -->
							  <td>{{ album.genre }}</td>
							  <td><a v-bind:href="'/albums/edit/' + album.id">Edit</a></td>
							  <td><a href="#" v-on:click.stop="deleteAlbum(album)" class="delete-link">Delete</a></td>
							</tr>
						</tbody>
					</table>
					
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
    
    	data() {
    		return {
				albums: null,
				bands: null,
				currentBand: '',
				orderByProperty: 'name',
				sortProperty: 'asc',
			}
    	},
        mounted() {
            console.log('Albums Component mounted.');
        },
        created() {
			this.fetchBands()
			this.fetchAlbums()
		},
		watch: {
			currentBand: 'fetchAlbums',
		},
        methods: {
        	fetchBands() {
				var self = this
				axios.get('bands').then(function (response) {
					console.log('Bands loaded');
					self.bands = response.data.data;
				})
			},
			order(field, sort) {
				
				if (field == this.orderByProperty) {
					this.sortProperty = (this.sortProperty == 'desc') ? 'asc' : 'desc';
				} else {
					this.sortProperty = 'desc';
				}
				
				this.orderByProperty = field;
								
				this.fetchAlbums();
			},
			fetchAlbums() {
				var self = this
				var url = 'albums?orderby=' + self.orderByProperty + '&sort=' + self.sortProperty;
											
				if (this.currentBand) {
					url += '&band_id:equals=' + self.currentBand;
				}
				
				axios.get(url).then(function (response) {
					console.log('Albums loaded');
					self.albums = response.data.data;
				})
			},
			deleteAlbum(album) {
				var self = this
				axios.delete('albums/' + album.id).then(function (response) {
					console.log('Album deleted');
					self.albums.splice(self.albums.indexOf(album), 1)
				})
			}
		}
    }
</script>

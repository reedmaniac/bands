<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" v-if="band">
                    <div class="panel-heading">
                    	<h2>
                    		{{band.name}}
                    	</h2>
                    </div>
                    
                    <div class="panel-body">
						<div><strong>Start Date: </strong>{{ band.start_date }}</div>
						<div><strong>Still Active? </strong>{{ band.still_active }}</div>
						<div><strong><a :href="band.website">Website</a></strong></div>
						<div>
							<strong>
								<a v-bind:href="'/bands/edit/' + band.id">Edit</a>
								• 
								<a href="#" v-on:click.stop="deleteBand(band)" class="delete-link">Delete</a>
							</strong>
						</div>
					</div>
					
					<div class="panel-body">
						<h3>Albums</h3>
						<table class="albums">
							<thead>
								<th>Album</th>
								<th>Release Date</th>
								<th>Producer</th>
								<th># of Tracks</th>
								<th>Genre</th>
								<th>Edit</th>
								<th>Delete</th>
							</thead>
						
							<tbody v-if="!band.albums || band.albums.length == 0">
								<tr>
									<td colspan="7">No Albums for Band</td>
								</tr>
						
							</tbody>
						
							<tbody v-if="band.albums && band.albums.length > 0">
								<tr class="album" v-for="album in band.albums">
								  <td>
									<a v-bind:href="'/albums/' + album.id">
										{{ album.name }}
									</a>
								  </td>
								  <td>{{ album.release_date }}</td>
								  <td>{{ album.producer }}</td>
								  <td>{{ album.number_of_tracks }}</td>
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
    </div>
</template>

<script>
    export default {
    
    	data() {
    		return {
				band: null,
				bandId: this.id,
			}
    	},
    	props: [
    		'id'
    	],
        mounted() {
            console.log('ViewBand Component mounted.');
        },
        created() {
			this.fetchBand()
		},
        methods: {
			fetchBand() {
				var self = this
				var url = 'bands/' + self.bandId;
				
				axios.get(url).then(function (response) {
					console.log('Band loaded');
					self.band = response.data.data;
				})
			},
			deleteBand(band) {
				var self = this
				axios.delete('bands/' + band.id).then(function (response) {
					console.log('Band deleted');
					window.location = "/bands/";
				})
			},
			deleteAlbum(album) {
				var self = this
				axios.delete('albums/' + album.id).then(function (response) {
					console.log('Album deleted');
					self.band.albums.splice(self.band.albums.indexOf(album), 1)
				})
			}
		}
    }
</script>

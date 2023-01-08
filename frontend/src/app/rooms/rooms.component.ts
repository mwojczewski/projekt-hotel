import { Component, OnInit } from '@angular/core';
import { environment } from 'src/environments/environment';
import { AppService } from '../app.service';

@Component({
  selector: 'app-rooms',
  templateUrl: './rooms.component.html',
  styleUrls: ['./rooms.component.scss']
})
export class RoomsComponent implements OnInit {
  rooms: any;
  filteredRooms: any;
  selectedOptions!: number[];
  beds!: number;

  options = [
    { id: 1, name: 'Balkon' },
    { id: 2, name: 'Zwierzęta' },
    { id: 3, name: 'Śniadanie' }
  ];

  constructor(
    private appService: AppService
  ) { }

  ngOnInit(): void {
    this.getRooms();
  }

  onSearch() {
    this.filteredRooms = this.rooms.filter((room: any) => {
      if (this.beds > 0 && room.beds !== this.beds) {
        return false;
      }
      if (this.selectedOptions && this.selectedOptions.length > 0) {
        if (this.selectedOptions.includes(1) && !room.balcony) {
          return false;
        }
        if (this.selectedOptions.includes(2) && !room.pets_allowed) {
          return false;
        }
        if (this.selectedOptions.includes(3) && !room.breakfast) {
          return false;
        }
      }
      return true;
    });
  }

  private getRooms() {
    this.appService.getAllRooms().subscribe(
      response => {
        this.rooms = response;
        if (this.rooms.length > 0) {
          for (let room of this.rooms) {
            this.appService.getRoomPhotos(room.id).subscribe(
              response => room['mainPhoto'] = `${environment.apiUrl}/uploads/images/${response['roomPhotos'][0].name}`
            )
          }
          this.filteredRooms = this.rooms;
        }
      }
    )
  }
}

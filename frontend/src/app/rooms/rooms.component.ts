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

  constructor(
    private appService: AppService
  ) { }

  ngOnInit(): void {
    this.getRooms();
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
        }
      }
    )
  }
}

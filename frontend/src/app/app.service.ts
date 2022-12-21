import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class AppService {
  private apiUrl = `${environment.apiUrl}/api`;

  constructor(private http: HttpClient) { }

  getAllRooms() {
    return this.http.get(`${this.apiUrl}/rooms`);
  }

  getRoomDetails(roomId: string | null) {
    return this.http.get(`${this.apiUrl}/room/details/${roomId}`);
  }

  getRoomPhotos(roomId: string) {
    return this.http.get<any>(`${this.apiUrl}/room/photos/${roomId}`);
  }

  getRoomReservations(roomId: string | null) {
    return this.http.get<any>(`${this.apiUrl}/room/reservations/${roomId}`);
  }

  checkRoomReservations(roomId: string | null, start: string, end: string) {
    return this.http.get<any>(`${this.apiUrl}/room/reservations/check/${roomId}/${start}/${end}`);
  }

  createReservation(data: any) {
    return this.http.post(`${this.apiUrl}/room/reservations/create`, data)
  }
}

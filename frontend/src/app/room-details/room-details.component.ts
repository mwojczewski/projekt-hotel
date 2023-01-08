import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import {
  MAT_MOMENT_DATE_ADAPTER_OPTIONS, MAT_MOMENT_DATE_FORMATS,
  MomentDateAdapter
} from '@angular/material-moment-adapter';
import { DateAdapter, MAT_DATE_FORMATS, MAT_DATE_LOCALE } from '@angular/material/core';
import { ActivatedRoute, Router } from '@angular/router';
import * as _moment from 'moment';
import 'moment/locale/fr';
import 'moment/locale/ja';
import 'moment/locale/pl';
import { environment } from 'src/environments/environment';
import { AppService } from '../app.service';

const moment = _moment;

@Component({
  selector: 'app-room-details',
  templateUrl: './room-details.component.html',
  styleUrls: ['./room-details.component.scss'],
  providers: [
    { provide: MAT_DATE_LOCALE, useValue: 'pl' },
    {
      provide: DateAdapter,
      useClass: MomentDateAdapter,
      deps: [MAT_DATE_LOCALE, MAT_MOMENT_DATE_ADAPTER_OPTIONS],
    },
    { provide: MAT_DATE_FORMATS, useValue: MAT_MOMENT_DATE_FORMATS },
  ],
})
export class RoomDetailsComponent implements OnInit {

  roomDetails: any;
  roomId!: string | null;
  apiUrl = `${environment.apiUrl}/uploads/images/`;
  depart = new FormControl();
  return = new FormControl();
  roomReservations = [];
  dateError = false;
  newReservationForm!: FormGroup;
  successMsg = false;
  errorMsg = false;
  minDate = new Date();

  constructor(
    private route: ActivatedRoute,
    private appService: AppService,
    private fb: FormBuilder,
    private router: Router
  ) { }

  ngOnInit(): void {
    this.roomId = this.route.snapshot.paramMap.get('id');
    this.getRoomDetails();
    this.getRoomReservations();

    this.newReservationForm = this.fb.group({
      depart: [null, Validators.required],
      return: [null, Validators.required],
    })
  }

  myFilter = (d: Date | null): boolean => {
    const time = (d || moment()).toISOString();
    return !this.roomReservations.find(x => moment(x['starts_at'], "YYYY-MM-DD").toISOString() <= time && time <= moment(x['ends_at'], "YYYY-MM-DD").toISOString())
  }

  createReservation() {
    this.appService.checkRoomReservations(this.roomId, moment(this.newReservationForm.controls['depart'].value!).format("YYYY/MM/DD"), moment(this.newReservationForm.controls['return'].value!).format("YYYY/MM/DD"))
      .subscribe(res => {
        if (res.conflicts > 0) {
          this.dateError = true;
          this.getRoomReservations();
          setTimeout(() => this.dateError = false, 8000)
        } else {
          let data = {
            room_id: parseInt(this.roomId!),
            starts_at: moment(this.newReservationForm.controls['depart'].value!).format("YYYY.MM.DD"),
            ends_at: moment(this.newReservationForm.controls['return'].value!).format("YYYY.MM.DD")
          }
          this.appService.createReservation(data).subscribe(resp => {
            this.getRoomReservations();
            this.newReservationForm.reset();
            this.successMsg = true;
            setTimeout(() => this.successMsg = false, 8000)
          }, error => {
            this.errorMsg = true;
            if (error.message = "range_overlap") {
              this.getRoomReservations();
              this.dateError = true;
            }
            setTimeout(() => {
              this.errorMsg = false;
              this.dateError = false;
            }, 8000)
          })
        }
      })
  }

  private getRoomDetails() {
    this.appService.getRoomDetails(this.roomId).subscribe(
      response => {
        if (response == null) {
          this.router.navigate(['/404'])
        }
        this.roomDetails = response;
        this.roomDetails.price = this.roomDetails.price / 100;
      }
    )
  }

  private getRoomReservations() {
    this.appService.getRoomReservations(this.roomId).subscribe(response => this.roomReservations = response.reservations)
  }
}

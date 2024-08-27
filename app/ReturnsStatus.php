<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReturnsStatus extends Model
{
    const REQUESTED = 1;
    const TO_PICK_UP = 2;
    const INDICATE_STORE = 3;
    const TO_SHIP = 4;
    const TO_DIAGNOSE = 5;
    const REFUND_APPROVED = 6;
    const TO_PRINT_CRF = 7;
    const REFUND_IN_PROCESS = 8;
    const TO_SOR = 9;
    const TO_RECEIVE_SOR = 10;
    const REFUND_COMPLETE = 11;
    const RETURN_REJECTED = 12;
    const TO_PRINT_SSR = 13;
    const TO_SHIP_BACK = 14;
    const RETURN_COMPLETED = 15;
    const REPAIR_APPROVED = 16;
    const REPAIR_COMPLETE = 17;
    const TO_SCHEDULE = 18;
    const TO_PRINT_PF = 19;
    const FOR_REPLACEMENT_OPS = 20;
    const REPLACEMENT_COMPLETE = 21;
    const TO_SCHEDULE_AFTERSALES = 22;
    const TO_SCHEDULE_LOGISTICS = 23;
    const TO_DROP_OFF = 24;
    const TO_CREATE_CRF = 25;
    const FOR_REPLACEMENT_SDM = 26;
    const FOR_REPLACEMENT = 27;
    const CANCELLED = 28;
    const TO_RECEIVE = 29;
    const TO_CLOSE = 30;
    const RECEIVED = 31;
    const TO_PRINT_SRR = 32;
    const RETURN_DELIVERY_DATE = 33;
    const TO_RECEIVE_RMA = 34;
    const TO_RECEIVE_SC = 35;
    const TO_RECEIVE_BY_SERVICE_CENTER = 36;
    const RMA_RECEIVED = 37;
    const FOR_WARRANTY_CLAIM = 38;
    const TO_ASSIGN_INC = 39;
    const TO_TEST = 40;
    protected $table = 'warranty_statuses';
}

'use strict';
const assert=require('node:assert/strict');
const {openingStatus,groupRequestError}=require('../assets/site.js');
const config=require('../data/site.json');
for(const [date,open,part] of [
 ['2026-10-19T06:59:00Z',false,'10:00'],['2026-10-19T07:00:00Z',true,'24:00'],
 ['2026-10-18T08:59:00Z',false,'12:00'],['2026-10-18T09:00:00Z',true,'24:00'],
 ['2026-10-23T20:59:00Z',true,'24:00'],['2026-10-23T21:00:00Z',false,'12:00'],
 ['2026-10-25T09:59:00Z',false,'12:00'],['2026-10-25T10:00:00Z',true,'24:00'],
 ['2026-03-29T08:59:00Z',false,'12:00'],['2026-03-29T09:00:00Z',true,'24:00']]){
 const state=openingStatus(new Date(date),config.hours);assert.equal(state.open,open,date);assert.ok(state.text.includes(part),date);
}
const valid={name:'Test local',phone:'0700000000',email:'',date:'2026-12-01',guests:'5',contact_permission:'on'};
assert.equal(groupRequestError(valid,'2026-10-18'),'');
assert.equal(groupRequestError({...valid,email:'sample@example.test'},'2026-10-18'),'');
assert.ok(groupRequestError({...valid,email:'invalid'},'2026-10-18'));
assert.ok(groupRequestError({...valid,contact_permission:''},'2026-10-18'));
assert.ok(groupRequestError({...valid,date:'2026-02-30'},'2026-01-01'));
console.log('PASS:10 opening/DST/midnight cases; optional email and form regressions.');

import React from 'react';
import Chevron from './chevronRight';
import Circle from './circleIcon';
export default function MyTicket(props) {

  return (
    <tr onClick={() => {

      props.setView('myDetailView');
      props.setTicketId(props.value.id);

    }}>
      <td>
        <strong className="ticketTitle">{props.value.ticketTitle}</strong>
        <div className="container">
          <div className="row">
            <div className="col-sm">
              <small className="ticketInfo">Status: {props.value.statusCode}</small>
            </div>
            <div className="col-sm">
              <small className="ticketInfo">Type: {props.value.ticketType}</small>
            </div>

          </div>

          <div className="row">
            <div className="col-sm">
              <small className="ticketInfo">Assignee: {props.value.assigneeName}</small>
            </div>
            <div className="col-sm">
              <small className="ticketInfo">Priority: {props.value.priorityLevel}</small>
            </div>
          </div>

          <div className="row">
            <div className="col-sm">

              <small className="ticketInfo">Due Date: {props.value.dueDate}</small>

            </div>
            <div className="col-sm">
              <small className="ticketInfo">Created At: {props.value.createdAt}</small>
            </div>
          </div>

        </div>
        <div className="">
          <Circle/>
          <Chevron/>
        </div>
      </td>
    </tr>
  );
}

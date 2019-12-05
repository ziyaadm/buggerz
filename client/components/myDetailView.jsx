import React from 'react';

export default class MyDetailView extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      ticketDetails: ['']
    };
    this.getMyTickets = this.getMyTickets.bind(this);
  }

  componentDidMount() {
    this.getMyTickets();
  }

  getMyTickets() {

    const request = `/api/tickets?projectId=0&ticketId=${this.props.ticketId}`;

    fetch(request)
      .then(res => res.json())
      .then(data => this.setState({ ticketDetails: data }))
      .catch(err => console.error('Fetch failed!', err));
  }

  render() {
    const details = this.state.ticketDetails[0];

    return (
      <div>
        <button onClick={() => this.props.setView('myTicketList')}>Back to My Ticket List</button>
        <h1 className="text-center">{details.title}</h1>
        <div className="container text-center">
          <div className="row">
            <div className="col-sm">
              <small>Assignee: {details.assigneeName}</small>
            </div>
            <div className="col-sm">
              <small>Priority: {details.priorityLevel}</small>
            </div>
          </div>

          <div className="row">
            <div className="col-sm">
              <small>Due Date: {details.dueDate}</small>
            </div>
            <div className="col-sm">
              <small>Created At: {details.createdAt}</small>
            </div>
          </div>

          <div className="row">
            <div className="col-sm">
              <small>Status: {details.statusCode}</small>
            </div>

          </div>

        </div>
        <br></br>
        <p className="text-center">{details.description}</p>
        <img src={details.fileUrl} className="img-fluid" alt="Responsive image"></img>

      </div>
    );
  }
}

import React from 'react';
import TeamTicket from './teamTicket';

export default class TeamTicketList extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      teamTickets: []
    };
  }

  componentDidMount() {
    fetch('/api/health-check')
      .then(res => res.json())
      .then(data => this.setState({ message: data.message || data.error }))
      .catch(err => this.setState({ message: err.message }))
      .finally(() => this.setState({ isTesting: false }));
    this.getTeamTickets();
  }

  getTeamTickets() {

    const request = `/api/tickets?projectId=${this.props.projectId}`;

    fetch(request)
      .then(res => res.json())
      .then(data => this.setState({ teamTickets: data }))
      .catch(err => console.error('Fetch failed!', err));
  }

  render() {
    const teamTicketArray = this.state.teamTickets.map((value, index) => (
      <TeamTicket
        key={index}
        value={value}
        setView={this.props.setView}

      />
    ));

    return (
      <table className="table table-bordered">
        <tbody>{teamTicketArray}</tbody>
      </table>
    );
  }
}

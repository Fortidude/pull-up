import React from "react";
import { Route, Redirect, withRouter } from "react-router-dom";
import { Menu, Icon, Layout } from 'antd';
const { Header, Content, Sider } = Layout;

import Main from './Screens/Main';
import Login from './Screens/Login';
import ExerciseList from './Screens/Exercise/List';

const isAuthorized = () => {
	return !!window.localStorage.getItem('_t');
};

const PrivateRoute = ({ component: Component, ...rest }) => (
	<Route {...rest} render={(props) => (
		isAuthorized() === true
			? <Component {...props} />
			: <Redirect to='/login' />
	)} />
)

class Navigation extends React.Component {

	navigateToHome = () => this.props.history.push('/');
	navigateToExerciseList = () => this.props.history.push('/exercise-list');

	render() {
		return (

			<Layout>
				<Sider width={250}>
					<Menu
						defaultSelectedKeys={['1']}
						defaultOpenKeys={['sub1']}
						mode="inline"
					>
						<Menu.Item onClick={this.navigateToHome} key="home"><span><Icon type="home" /><span>Home</span></span></Menu.Item>
						<Menu.Divider />
						<Menu.SubMenu key="exercise_list" title={<span><Icon type="bars" /><span>Exercises</span></span>}>
							<Menu.Item key="el_list" onClick={this.navigateToExerciseList}>Lista</Menu.Item>
							<Menu.Item key="el_add" >Dodaj</Menu.Item>
						</Menu.SubMenu>

						<Menu.SubMenu key="users_list" title={<span><Icon type="user" /><span>Users</span></span>}>
							<Menu.Item key="ul_list" onClick={this.navigateToExerciseList}>Lista</Menu.Item>
							<Menu.Item key="ul_add" >Dodaj</Menu.Item>
						</Menu.SubMenu>
					</Menu>

				</Sider>
				<Content>
					<div className="">
						<Route exact path="/login" component={Login} />
						<PrivateRoute exact path="/" component={Main} />
						<PrivateRoute exact path="/exercise-list" component={ExerciseList} />
						<PrivateRoute extact path="/exercise" component={ExerciseList} />
					</div>
				</Content>
			</Layout>

		);
	}
}

export default withRouter(Navigation);

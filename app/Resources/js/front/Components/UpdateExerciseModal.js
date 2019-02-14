import React from 'react';
import { Form, Switch, Input, Modal } from 'antd';

class UpdateExerciseModal extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            id: null,
            name: "",
            description: "",
            isCardio: false,
            visible: !!props.exercise,
        }
    }

    componentWillReceiveProps(nextProps) {
        const { exercise } = nextProps;
        if (!exercise) {
            return;
        }

        this.setState({
            id: exercise.id,
            name: exercise.name,
            description: exercise.description,
            isCardio: exercise.is_cardio,
            visible: !!exercise
        })
    }

    handleOk = () => {
        this.props.onSuccess(this.state.id, this.state.name, this.state.description, this.state.isCardio);
        this.cancel();
    }

    cancel = () => {
        this.setState({ id: null, name: "", description: "", isCardio: false, visible: false });
        this.props.onClose && this.props.onClose();
    }

    render() {
        return (
            <React.Fragment>
                <Modal
                    title="Update exercise"
                    visible={this.state.visible}
                    onOk={this.handleOk}
                    onCancel={this.cancel}
                >

                    <Form onSubmit={this.handleSubmit} className="update-form">
                        <Form.Item>
                            <Input placeholder="Name" allowClear
                                value={this.state.name}
                                onChange={event => this.setState({ name: event.target.value })} />
                        </Form.Item>
                        <Form.Item>
                            <Input placeholder="Description" allowClear
                                value={this.state.description}
                                onChange={event => this.setState({ description: event.target.value })} />
                        </Form.Item>
                        <Form.Item className="">
                            <Switch
                                className="large"
                                checkedChildren={"Cardio Mode"}
                                unCheckedChildren={"Classic Mode"}
                                checked={this.state.isCardio}
                                onChange={(value) => this.setState({ isCardio: !!value })}
                            />
                        </Form.Item>
                    </Form>

                </Modal>
            </React.Fragment>
        )
    }
}

export default UpdateExerciseModal;

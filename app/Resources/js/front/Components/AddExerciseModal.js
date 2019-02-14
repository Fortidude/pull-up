import React from 'react';
import { Button, Form, Switch, Input, Modal } from 'antd';

class AddExerciseModal extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            name: "",
            isCardio: false,
            visible: false,
        }
    }

    shouldComponentUpdate(nextProps, nextState) {
        return this.state.name !== nextState.name
        || this.state.isCardio !== nextState.isCardio
        || this.state.visible !== nextState.visible;
    }

    handleOk = () => {
        this.props.onSuccess(this.state.name, this.state.isCardio);
        this.cancel();
    }

    cancel = () => {
        this.setState({ name: "", isCardio: false, visible: false });
    }

    render() {
        return (
            <React.Fragment>
                <Modal
                    title="Create exercise"
                    visible={this.state.visible}
                    onOk={this.handleOk}
                    onCancel={this.cancel}
                >

                    <Form onSubmit={this.handleSubmit} className="create-form">
                        <Form.Item>
                            <Input placeholder="Name" allowClear
                                value={this.state.name}
                                onChange={event => this.setState({ name: event.target.value })} />
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

                <div className="AddExerciseButton">
                    <Button
                        onClick={() => this.setState({ visible: true })}
                        size="large"
                        shape="circle"
                        type="primary"
                        icon="plus" />
                </div>
            </React.Fragment>
        )
    }
}

const WrappedAddExerciseModal = Form.create({ name: 'normal_add_exercise' })(AddExerciseModal);
export default WrappedAddExerciseModal;

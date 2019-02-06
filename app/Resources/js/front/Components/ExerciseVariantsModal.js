import React from 'react';
import { Button, Form, Switch, Input, Modal } from 'antd';

class ExerciseVariantsModal extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            list: props.exerciseVariants,
            editModeKey: null,
            name: "",
            id: null,

            newVariantMode: false,
            newVariantName: null
        }
    }

    close = () => {
        this.setState({
            editModeKey: null,
            newVariantMode: false,
            newVariantName: null,
            name: "",
            id: null
        });

        this.props.onClose();
    }

    editMode = (key, name, id) => {
        this.setState({ editModeKey: key, name, id })
    }

    saveEditMode = (key) => {
        const { list } = this.state;
        list.forEach((item, key) => {
            if (item.id === this.state.id) {
                item.name = this.state.name;
            }
        })
        this.setState({ editModeKey: null, name: '', id: null, list });
    }

    remove = (key) => {
        const { list } = this.state;
        list.splice(key, 1);
        this.setState({ list });
    }

    addNewVariant = () => {
        const { list, newVariantName } = this.state;
        const upperCase = newVariantName.toLocaleUpperCase();
        if (!newVariantName || -1 < list.findIndex((item) => item.name.toLocaleUpperCase() === upperCase)) {
            return;
        }

        list.push({ id: newVariantName, name: newVariantName });
        this.setState({ list, newVariantName: "", newVariantMode: false });
    }

    render() {
        return (
            <Modal
                title="Exercise variants list"
                visible={this.props.visible}
                onOk={this.handleOk}
                footer={[
                    <Button key="back" type="primary" onClick={this.close}>Close</Button>
                ]}
            >

                <div className="exerciseVariants">
                    {this.state.list.map((variant, key) => this.renderVariant(variant, key))}
                </div>

                <div className="newVariant">
                    {!this.state.newVariantMode && <Button shape="circle-outline" icon="plus" type="primary" onClick={() => this.setState({ newVariantMode: true, editModeKey: null })} />}
                    {this.state.newVariantMode && <Button shape="circle-outline" icon="save" type="primary" onClick={this.addNewVariant} />}
                    {this.state.newVariantMode && <Button shape="circle-outline" icon="close" type="danger" onClick={() => this.setState({ newVariantMode: false, editModeKey: null })} />}
                    {this.state.newVariantMode && <Input placeholder="Name" allowClear
                        value={this.state.newVariantName}
                        onChange={event => this.setState({ newVariantName: event.target.value })} />}
                </div>

            </Modal>
        )
    }

    renderVariant = (variant, key) => {
        const editMode = this.state.editModeKey === key;
        const turnOnEditMode = () => this.editMode(key, variant.name, variant.id);

        return (
            <div key={key} className={editMode ? "edit" : ""}>
                <div>#{key + 1}</div>
                <div onDoubleClick={turnOnEditMode}>
                    <span className="title">{variant.name}</span>
                    <Input placeholder="Name" allowClear
                        value={this.state.name}
                        onChange={event => this.setState({ name: event.target.value })} />
                </div>
                <div>{variant.description}</div>
                <div>

                    {!editMode && <Button shape="circle" icon="edit" onClick={turnOnEditMode} />}
                    {!editMode && <Button shape="circle" icon="delete" type="danger" onClick={() => this.remove(key)} />}
                    {editMode && <Button shape="circle" icon="save" type="primary" onClick={() => this.saveEditMode()} />}
                </div>
            </div >
        );
    }
}

const WrappedExerciseVariantsModal = Form.create({ name: 'normal_add_exercise' })(ExerciseVariantsModal);
export default WrappedExerciseVariantsModal;

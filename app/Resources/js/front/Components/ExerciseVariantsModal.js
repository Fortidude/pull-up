import React from 'react';
import { Button, Form, Switch, Input, Modal } from 'antd';
import { createExerciseVariant, removeExerciseVariant, updateExerciseVariant } from '../api';

class ExerciseVariantsModal extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            editModeKey: null,
            name: "",
            id: null,

            newVariantMode: false,
            newVariantName: null
        }
    }

    close = () => {
        this.props.onClose();
        this.setState({
            editModeKey: null,
            newVariantMode: false,
            newVariantName: null,
            name: "",
            id: null
        });
    }

    editMode = (key, name, id) => {
        this.setState({ editModeKey: key, name, id })
    }

    update = (variantId) => {
        updateExerciseVariant(this.props.exercise.id, variantId, this.state.name).then(() => {
            this.setState({ editModeKey: null, name: '', id: null });
            this.props.onChange && this.props.onChange();
        });
    }

    remove = (variantId) => {
        const onRemove = () => removeExerciseVariant(this.props.exercise.id, variantId).then(() => {
            this.props.onChange && this.props.onChange();
        });

        Modal.confirm({
            title: 'Are you sure delete this exercise variant?',
            okText: 'Yes',
            okType: 'danger',
            cancelText: 'No',
            onOk() {
                onRemove();
            },
            onCancel() {

            },
        });
    }

    addNewVariant = () => {
        const { list, newVariantName } = this.state;
        const upperCase = newVariantName.toLocaleUpperCase();
        if (!newVariantName || -1 < this.props.exercise.exercise_variants.findIndex((item) => item.name.toLocaleUpperCase() === upperCase)) {
            return;
        }

        createExerciseVariant(this.props.exercise.id, newVariantName).then(() => {
            // list.push({ id: newVariantName, name: newVariantName });
            this.setState({ list, newVariantName: "", newVariantMode: false });

            this.props.onChange && this.props.onChange();
        })
    }

    render() {
        let list = [];
        if (this.props.exercise && this.props.exercise.exercise_variants) {
            list = this.props.exercise.exercise_variants;
            list.sort((a, b) => {
                const nameA = a.name.toUpperCase();
                const nameB = b.name.toUpperCase();

                return nameA > nameB;
            });
        }

        return (
            <Modal
                title="Exercise variants list"
                visible={!!this.props.exercise}
                onOk={this.handleOk}
                onCancel={this.close}
                footer={[
                    <Button key="back" type="primary" onClick={this.close}>Close</Button>
                ]}
            >

                {!!this.props.exercise && <div className="exerciseVariants">
                    {list.map((variant, key) => this.renderVariant(variant, key))}
                </div>}

                <div className="newVariant">
                    {!this.state.newVariantMode && <Button shape="circle-outline" icon="plus" type="primary" onClick={() => this.setState({ newVariantMode: true, editModeKey: null })} />}
                    {this.state.newVariantMode && <Button shape="circle-outline" icon="save" type="primary" onClick={this.addNewVariant} />}
                    {this.state.newVariantMode && <Input placeholder="Name" allowClear
                        value={this.state.newVariantName}
                        onKeyDown={(ev) => ev.keyCode === 13 && this.addNewVariant()}
                        onChange={event => this.setState({ newVariantName: event.target.value })} />}
                    {this.state.newVariantMode && <Button className="cancel" shape="circle-outline" icon="close" type="danger" onClick={() => this.setState({ newVariantMode: false, editModeKey: null })} />}
                </div>

            </Modal>
        )
    }

    renderVariant = (variant, key) => {
        const editMode = this.state.editModeKey === key;
        const turnOnEditMode = () => this.editMode(key, variant.name, variant.id);
        const turnOffEditMode = () => this.editMode(null, null, null);

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
                    {!editMode && <Button shape="circle" icon="delete" type="danger" onClick={() => this.remove(variant.id)} />}
                    {editMode && <Button shape="circle" icon="save" type="primary" onClick={() => this.update(variant.id)} />}
                    {editMode && <Button shape="circle" icon="close" type="danger" onClick={turnOffEditMode} />}
                </div>
            </div >
        );
    }
}

const WrappedExerciseVariantsModal = Form.create({ name: 'normal_add_exercise' })(ExerciseVariantsModal);
export default WrappedExerciseVariantsModal;

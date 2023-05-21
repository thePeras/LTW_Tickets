<?php


function drawDepartmentTable(array $departments)
{
    ?>
    <table class="department-table">
    <thead>
        <tr>
            <th>
                Name
            </th>
            <th>
                Description
            </th>
            <th>
                Members
            </th>
            <th>
                </th>
                <th>
                </th>
        </tr>
    </thead>

        <tbody class="department-tbody">
            <?php foreach ($departments as $department) :?>
                <tr class="department-entry">
                    <td>
                        <p class="department-name"><?php echo htmlspecialchars($department->name)?></p>
                    </td>
                    <td>
                        <p class="department-description"><?php echo htmlspecialchars($department->description)?></p>
                    </td>
                    <td>
                        <p class="department-members"><?php echo htmlspecialchars(strval($department->count))?> Members</p>
                    </td>
                    <td>
                        <i class="ri-edit-line icon" onclick="makeEditModal('<?php echo htmlspecialchars($department->name)?>')"></i>
                    </td>
                    <td>
                        <i class="ri-delete-bin-line icon delete" onclick="makeDeleteModal('<?php echo htmlspecialchars($department->name)?>')")></i>
                    </td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <?php

}

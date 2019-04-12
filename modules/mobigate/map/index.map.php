<?php
ZenView::section('Phân phối game từ mobigate', function() {
    ZenView::display_breadcrumb();
	ZenView::display_message('result');
	if(isset(ZenView::$D['appid'])) {
		ZenView::block('Chọn chuyên mục', function() {
			ZenView::padded(function() {
                ZenView::display_message('select-cat');
                echo '<form method="POST">';
				echo '<div class="form-group">
						<select class="chzn-select" name="catid" id="input-parent">';
                foreach (ZenView::$D['tree_folder'] as $id => $name) {
                    echo '<option value="' . $id . '" ' . (ZenView::$D['blog']['parent'] == $id ? 'selected':''). '>' . $name . '</option>';
                }
                echo '</select></div>';
                echo '<div class="form-actions">
						<input type="submit" value="Phân phối game này" class="btn btn-blue" />
					</div>';
				echo '</form>';
            });
		});
	} else {
		//list app
		ZenView::block('Danh sách game phân phối', function() {
            ZenView::display_message('list-app');
            if (!empty(ZenView::$D['items'])) {
                echo '<table class="dTable">';
                echo '	<thead><tr>
                            <th><div>Icon</div></th>
                            <th><div>Tên</div></th>
                            <th><div>L.xem</div></th>
                            <th><div>L.tải</div></th>
                            <th><div>Mô tả</div></th>
                            <th><div></div></th>
                        </tr></thead>';
                foreach(ZenView::$D['items'] as  $item) {
                    echo '<tr>
                            <td><img src="'.$item->avatar.'" alt="img" height="50px" weight="50px"/></td>
                            <td><a href="http://mobigate.vn/kho-game/view/content-i' . $item->contentId . '" title="Xem trên Mobigate.vn" target="_blank">'.$item->name.'</a></td>
                            <td>'.$item->view.'</td>
                            <td>'.$item->download.'</td>
                            <td>'.$item->shortDescription.'</td>
                            <td>' . (model('mobigate')->app_exist($item->requestId) ? '<button class="btn btn-default" disabled>Phân phối</button>':'<a href="'. ZenView::$D['base_url'] .'&appid='.$item->requestId.'" class="btn btn-blue">Phân phối</a>') . '</td>
                        </tr>';
                }
                echo '</table>';
            }
		});
	}
});